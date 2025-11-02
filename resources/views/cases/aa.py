from flask import Flask, render_template, request, Response, redirect, url_for, session, make_response
from markupsafe import escape, Markup
from PIL import Image, ImageDraw
import io
import base64
import random
import datetime
import time
import string
import threading
import math
import secrets
import hashlib
import re
import logging
import os

app = Flask(__name__)

# Security Configuration
app.secret_key = secrets.token_hex(32)  # Generate secure random key
app.permanent_session_lifetime = datetime.timedelta(hours=12)

# Security settings
app.config.update(
    SESSION_COOKIE_SECURE=False,  # Disable for local development/bot testing
    SESSION_COOKIE_HTTPONLY=True,
    SESSION_COOKIE_SAMESITE='Strict',
    PERMANENT_SESSION_LIFETIME=datetime.timedelta(hours=12)
)

# Rate limiting and security constants
MAX_MESSAGE_LENGTH = 999
MAX_USERNAME_LENGTH = 15
MIN_USERNAME_LENGTH = 3
MAX_LOGIN_ATTEMPTS = 5
LOGIN_COOLDOWN = 300  # 5 minutes
CAPTCHA_TIMEOUT = 300  # 5 minutes

# Security tracking
login_attempts = {}  # {ip: {count, last_attempt}}
blocked_ips = set()

# Disable debug mode and set up secure logging
app.config['DEBUG'] = False
logging.basicConfig(level=logging.WARNING, format='%(asctime)s - %(levelname)s - %(message)s')

# Data structures for chat functionality (random one-to-one chat removed)

# Group chat data structures
group_users = {}  # {session_id: {username, ip, last_active, captcha_solved, last_message_time}}
group_messages = []  # [{time, username, message, type, timestamp}] where type is 'public' or 'private'
private_messages = {}  # {recipient_username: [{time, sender, message, timestamp}]}
active_group_connections = {}  # Track active group chat streaming connections
group_chat_lock = threading.Lock()  # Lock for thread safety in group chat

# Security helper functions
def is_ip_blocked(ip):
    """Check if IP is temporarily blocked due to too many failed attempts."""
    # Skip rate limiting for localhost during development/bot testing
    if ip in ['127.0.0.1', 'localhost', '::1']:
        return False

    if ip in blocked_ips:
        return True

    if ip in login_attempts:
        attempt_data = login_attempts[ip]
        if attempt_data['count'] >= MAX_LOGIN_ATTEMPTS:
            if time.time() - attempt_data['last_attempt'] < LOGIN_COOLDOWN:
                return True
            else:
                # Reset attempts after cooldown
                del login_attempts[ip]
    return False

def record_failed_attempt(ip):
    """Record a failed login attempt for rate limiting."""
    # Skip rate limiting for localhost during development/bot testing
    if ip in ['127.0.0.1', 'localhost', '::1']:
        return

    now = time.time()
    if ip not in login_attempts:
        login_attempts[ip] = {'count': 1, 'last_attempt': now}
    else:
        login_attempts[ip]['count'] += 1
        login_attempts[ip]['last_attempt'] = now
    
    if login_attempts[ip]['count'] >= MAX_LOGIN_ATTEMPTS:
        blocked_ips.add(ip)
        logging.warning(f"IP {ip} temporarily blocked after {MAX_LOGIN_ATTEMPTS} failed attempts")

def sanitize_input(text, max_length=None):
    """Sanitize user input to prevent XSS and injection attacks."""
    if not text:
        return ""
    
    # Strip whitespace
    text = text.strip()
    
    # Apply length limit
    if max_length and len(text) > max_length:
        text = text[:max_length]
    
    # Remove potentially dangerous characters but keep basic punctuation
    text = re.sub(r'[<>"\']', '', text)
    
    return text

def validate_username(username):
    """Strict username validation for security."""
    if not username:
        return False
    
    # Length check
    if len(username) < MIN_USERNAME_LENGTH or len(username) > MAX_USERNAME_LENGTH:
        return False
    
    # Character whitelist - only alphanumeric and underscores
    if not re.match(r'^[a-zA-Z0-9_]+$', username):
        return False
    
    return True

# Helper functions for chat functionality
def generate_client_id():
    """Generate a cryptographically secure random client ID."""
    return secrets.token_urlsafe(22)

def generate_random_gradient_css():
    """Generate a random CSS gradient similar to the examples."""
    angle = random.randint(0, 359)
    colors = []
    for _ in range(7):
        r = random.randint(0, 255)
        g = random.randint(0, 255)
        b = random.randint(0, 255)
        a = round(random.uniform(0.001, 0.999), 3)
        colors.append(f"rgba({r},{g},{b},{a})")
    gradient = f"linear-gradient({angle}deg, {', '.join(colors)})"
    return gradient

def get_utc_time():
    """Get the current UTC time in 24-hour format (HH:MM)."""
    now = datetime.datetime.now(datetime.timezone.utc)
    return now.strftime("%H:%M")

# Group chat helper functions
# Captcha removed

def is_valid_username(username):
    """Check if username is valid using strict validation."""
    return validate_username(username)

def is_username_taken(username):
    """Check if username is already taken by an active user."""
    with group_chat_lock:
        for session_data in group_users.values():
            if session_data['username'].lower() == username.lower():
                return True
    return False

def add_group_message(username, message, msg_type='public', recipient=None):
    """Add a message to group chat with security validation."""
    # Sanitize inputs
    username = sanitize_input(username, MAX_USERNAME_LENGTH)
    message = sanitize_input(message, MAX_MESSAGE_LENGTH)
    
    if recipient:
        recipient = sanitize_input(recipient, MAX_USERNAME_LENGTH)
    
    try:
        with group_chat_lock:
            time_str = get_utc_time()
            current_timestamp = time.time()
            msg_data = {
                'time': time_str,
                'username': username,
                'message': message,
                'type': msg_type,
                'timestamp': current_timestamp
            }
            
            if msg_type in ['public', 'system']:
                group_messages.append(msg_data)
                print(f"Message added to group_messages, total: {len(group_messages)}")
                # Clean up messages older than 24 hours
                cleanup_old_messages()
            elif msg_type == 'private' and recipient:
                if recipient not in private_messages:
                    private_messages[recipient] = []
                private_messages[recipient].append({
                    'time': time_str,
                    'sender': username,
                    'message': message,
                    'timestamp': current_timestamp
                })
                # Clean up private messages older than 24 hours
                cleanup_old_private_messages(recipient)
            
            print(f"add_group_message completed successfully")
    except Exception as e:
        print(f"Error in add_group_message: {e}")
        import traceback
        traceback.print_exc()

def cleanup_old_messages():
    """Remove messages older than 24 hours."""
    cutoff_time = time.time() - (24 * 60 * 60)  # 24 hours ago
    global group_messages
    group_messages = [msg for msg in group_messages if msg.get('timestamp', 0) > cutoff_time]

def cleanup_old_private_messages(recipient):
    """Remove private messages older than 24 hours for a specific recipient."""
    if recipient in private_messages:
        cutoff_time = time.time() - (24 * 60 * 60)  # 24 hours ago
        private_messages[recipient] = [msg for msg in private_messages[recipient] if msg.get('timestamp', 0) > cutoff_time]

def check_rate_limit(session_id):
    """Check if user is sending messages too quickly (rate limit: 1 message per 2 seconds)."""
    now = time.time()
    if session_id in group_users:
        last_message_time = group_users[session_id].get('last_message_time', 0)
        if now - last_message_time < 2:  # 2 second rate limit
            return False
        group_users[session_id]['last_message_time'] = now
    return True

def get_active_users():
    """Get list of active usernames (only currently connected or very recently active)."""
    now = time.time()
    with group_chat_lock:
        users = []
        for sid, data in group_users.items():
            # Consider connection active only with a fresh heartbeat; else fallback to recent activity
            conn = active_group_connections.get(sid)
            if conn and conn.get('count', 0) > 0 and (now - conn.get('timestamp', 0)) <= 10:
                users.append(data['username'])
            elif now - data.get('last_active', 0) <= 30:
                users.append(data['username'])
        # Deduplicate and sort case-insensitively for stable display
        users = list(dict.fromkeys(users))
        users.sort(key=lambda x: x.lower())
        return users

def cleanup_inactive_group_users():
    """Remove users inactive for more than 30 minutes."""
    now = time.time()
    inactive_users = []
    
    with group_chat_lock:
        for session_id, user_data in group_users.items():
            if now - user_data['last_active'] > 1800:  # 30 minutes
                inactive_users.append((session_id, user_data['username']))
    
    for session_id, username in inactive_users:
        with group_chat_lock:
            if session_id in group_users:  # Double check user still exists
                group_users.pop(session_id, None)
                # Add system message for user leaving
                add_group_message('System', f'{username} left the chat', 'system')
                # Remove private messages for this user
                private_messages.pop(username, None)

def prune_disconnected_users(grace=5):
    """Quickly remove users that no longer have an active streaming connection.
    This makes the Users dropdown decrease immediately on next send/refresh without JS.
    A small grace avoids flapping on transient reconnects.
    """
    now = time.time()
    to_remove = []
    with group_chat_lock:
        for sid, data in list(group_users.items()):
            conn = active_group_connections.get(sid)
            # Remove if no connection and inactive past grace
            if not conn and (now - data.get('last_active', 0)) > grace:
                to_remove.append((sid, data['username']))
            # Or if connection exists but appears stale (no heartbeat or zero count)
            elif conn and (conn.get('count', 0) <= 0 or (now - conn.get('timestamp', 0)) > grace + 2):
                active_group_connections.pop(sid, None)
                to_remove.append((sid, data['username']))
    for sid, uname in to_remove:
        with group_chat_lock:
            if sid in group_users:
                group_users.pop(sid, None)
                private_messages.pop(uname, None)
        add_group_message('System', f'{uname} left the chat', 'system')







def generate_clock_captcha():
    """Generate a clock captcha image with random time and noise lines"""
    # Random time generation
    hour = random.randint(1, 12)
    minute = random.choice([0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55])
    
    # Store correct answer in session
    session['captcha_hour'] = f"{hour:02d}"
    session['captcha_minute'] = f"{minute:02d}"
    
    # Image settings
    size = 200
    center = size // 2
    radius = 80
    
    # Create image with white background
    img = Image.new('RGB', (size, size), '#ffffff')
    draw = ImageDraw.Draw(img)
    
    # Draw clock circle with dotted outline like reference
    # Draw dotted circle by drawing small arcs
    num_dots = 60
    for i in range(0, num_dots, 2):  # Draw every other dot for dotted effect
        angle = math.radians(i * 6)  # 360/60 = 6 degrees per dot
        x = center + radius * math.cos(angle)
        y = center + radius * math.sin(angle)
        draw.ellipse([x-1, y-1, x+1, y+1], fill='#999999')
    
    # Draw hour markers as small lines
    for i in range(12):
        angle = math.radians(i * 30 - 90)  # -90 to start from 12 o'clock
        outer_x = center + (radius - 5) * math.cos(angle)
        outer_y = center + (radius - 5) * math.sin(angle)
        inner_x = center + (radius - 15) * math.cos(angle)
        inner_y = center + (radius - 15) * math.sin(angle)
        draw.line([inner_x, inner_y, outer_x, outer_y], fill='#333333', width=2)
    
    # Calculate hand angles
    hour_angle = math.radians((hour % 12) * 30 + minute * 0.5 - 90)
    minute_angle = math.radians(minute * 6 - 90)
    
    # Draw minute hand (longer, thin black line)
    minute_length = radius * 0.7
    minute_x = center + minute_length * math.cos(minute_angle)
    minute_y = center + minute_length * math.sin(minute_angle)
    draw.line([center, center, minute_x, minute_y], fill='#000000', width=2)
    
    # Draw hour hand (shorter, thicker black line)
    hour_length = radius * 0.5
    hour_x = center + hour_length * math.cos(hour_angle)
    hour_y = center + hour_length * math.sin(hour_angle)
    draw.line([center, center, hour_x, hour_y], fill='#000000', width=3)
    
    # Add center dot
    draw.ellipse([center-3, center-3, center+3, center+3], fill='#000000')
    
    # Add subtle noise lines to confuse bots (lighter, less obvious)
    for _ in range(random.randint(1, 2)):
        x1, y1 = random.randint(20, size-20), random.randint(20, size-20)
        x2, y2 = random.randint(20, size-20), random.randint(20, size-20)
        color = random.choice(['#e0e0e0', '#f0f0f0', '#d8d8d8'])
        draw.line([x1, y1, x2, y2], fill=color, width=1)
    
    # Add some fake hands to confuse bots (very subtle)
    for _ in range(random.randint(0, 1)):
        fake_angle = math.radians(random.randint(0, 359))
        fake_length = radius * random.uniform(0.2, 0.4)
        fake_x = center + fake_length * math.cos(fake_angle)
        fake_y = center + fake_length * math.sin(fake_angle)
        draw.line([center, center, fake_x, fake_y], fill='#cccccc', width=1)
    
    # Convert to base64 for embedding
    buffer = io.BytesIO()
    img.save(buffer, format='PNG')
    img_str = base64.b64encode(buffer.getvalue()).decode()
    
    return img_str

# Security middleware
@app.before_request
def security_checks():
    """Apply security checks to all requests."""
    client_ip = request.remote_addr or 'unknown'
    
    # Check if IP is blocked
    if is_ip_blocked(client_ip):
        logging.warning(f"Blocked request from IP: {client_ip}")
        return make_response("Too many failed attempts. Try again later.", 429)

# Add security headers to all responses
@app.after_request
def add_security_headers(response):
    # Prevent external clickjacking but allow same-origin frames  
    response.headers['X-Frame-Options'] = 'SAMEORIGIN'
    # Prevent MIME sniffing
    response.headers['X-Content-Type-Options'] = 'nosniff'
    # XSS protection
    response.headers['X-XSS-Protection'] = '1; mode=block'
    # Referrer policy for privacy
    response.headers['Referrer-Policy'] = 'no-referrer'
    # Content Security Policy - secure but functional
    response.headers['Content-Security-Policy'] = "default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self'; img-src 'self' data:; connect-src 'self'; form-action 'self'; frame-src 'self';"
    return response

# Group chat routes
@app.route('/group-login', methods=['GET', 'POST'])
def group_login():
    client_ip = request.remote_addr or 'unknown'
    
    if request.method == 'POST':
        username = sanitize_input(request.form.get('username', ''), MAX_USERNAME_LENGTH)
        
        # Validate username
        if not is_valid_username(username):
            record_failed_attempt(client_ip)
            return render_template('group_login.html', 
                                 error="Username must be 3-15 characters long and contain only letters, numbers, and underscores.")
        
        # Check if username is taken
        if is_username_taken(username):
            record_failed_attempt(client_ip)
            return render_template('group_login.html', 
                                 error="Username is already taken. Please choose another.")
        
        # Prepare pending login then redirect to captcha
        session_id = generate_client_id()
        print(f"Preparing pending session for user: {username}, session_id: {session_id}")
        try:
            # Store pending username/session for captcha step
            session.permanent = True
            session['pending_session_id'] = session_id
            session['pending_username'] = username
            # Clear any previous chat session to avoid confusion
            session.pop('session_id', None)
            session.pop('username', None)
            return redirect(url_for('captcha'))
        except Exception as e:
            print(f"Error preparing session for captcha: {e}")
            return render_template('group_login.html', 
                                 error=f"Error preparing session: {e}")
    
    # GET request - show login form
    return render_template('group_login.html')

@app.route('/captcha', methods=['GET', 'POST'])
def captcha():
    client_ip = request.remote_addr or 'unknown'
    
    # Check if captcha session has expired (only if one was previously generated)
    captcha_time = session.get('captcha_generated_time', 0)
    if captcha_time > 0 and time.time() - captcha_time > CAPTCHA_TIMEOUT:
        session.clear()
        return redirect(url_for('group_login'))
    
    if request.method == 'POST':
        user_hour = sanitize_input(request.form.get('hour', ''), 2)
        user_minute = sanitize_input(request.form.get('minute', ''), 2)
        
        correct_hour = session.get('captcha_hour', '')
        correct_minute = session.get('captcha_minute', '')
        
        if user_hour == correct_hour and user_minute == correct_minute:
            # Clock captcha passed - create the actual group chat session
            pending_session_id = session.get('pending_session_id')
            pending_username = session.get('pending_username')
            
            if pending_session_id and pending_username:
                try:
                    print(f"Captcha passed for user: {pending_username}, creating session: {pending_session_id}")
                    group_users[pending_session_id] = {
                        'username': pending_username,
                        'ip': request.remote_addr,
                        'last_active': time.time(),
                        'captcha_solved': True,
                'last_message_time': 0,
                        'last_message_time': 0
                    }
                    
                    # Add system messages
                    add_group_message('System', f'{pending_username} joined the chat', 'system')
                    if len(group_messages) <= 2:  # First few users
                        add_group_message('System', f'Hello {pending_username}! Welcome to Ableonion Chat.', 'system')
                        add_group_message('System', 'The topic is general discussion with freedom of speech.', 'system')
                        add_group_message('System', 'Refresh the list of current users for private messages by sending an empty message.', 'system')
                        add_group_message('System', 'Use command /ignore <user> to hide all messages from a particular user.', 'system')
                    
                    # Set final session values
                    session['session_id'] = pending_session_id
                    session['username'] = pending_username
                    
                    # Clear pending and captcha data
                    session.pop('pending_session_id', None)
                    session.pop('pending_username', None)
                    session.pop('captcha_hour', None)
                    session.pop('captcha_minute', None)
                    
                    # Set cookies
                    resp = redirect(url_for('group_chat', session_id=pending_session_id))
                    max_age = int(datetime.timedelta(hours=12).total_seconds())
                    resp.set_cookie('gid', pending_session_id, max_age=max_age, samesite='Lax', httponly=True)
                    resp.set_cookie('guser', pending_username, max_age=max_age, samesite='Lax', httponly=True)
                    return resp
                    
                except Exception as e:
                    print(f"Error creating session after captcha: {e}")
                    clock_image = generate_clock_captcha()
                    return render_template('captcha.html', 
                                         clock_image=clock_image,
                                         error=f"Error creating session: {e}")
            else:
                # No pending session - redirect to login
                return redirect(url_for('group_login'))
        else:
            # Wrong captcha answer - record failed attempt and generate new captcha
            record_failed_attempt(client_ip)
            clock_image = generate_clock_captcha()
            session['captcha_generated_time'] = time.time()  # Reset timeout
            return render_template('captcha.html', 
                                 clock_image=clock_image,
                                 error="Wrong time, try again")
    
    # Check if there's a pending session
    if not session.get('pending_username') or not session.get('pending_session_id'):
        return redirect(url_for('group_login'))
    
    # Generate new captcha for GET request
    clock_image = generate_clock_captcha()
    session['captcha_generated_time'] = time.time()  # Set timeout
    return render_template('captcha.html', clock_image=clock_image)

@app.route('/group-chat/<session_id>', methods=['GET', 'POST'])
def group_chat(session_id):
    print(f"group_chat called with session_id: {session_id}, method: {request.method}")
    
    try:
        # Check if session is valid; attempt self-heal from Flask session and fallback cookies
        if session_id not in group_users:
            if session.get('session_id') == session_id and session.get('username'):
                with group_chat_lock:
                    group_users[session_id] = {
                        'username': session['username'],
                        'ip': request.remote_addr,
                        'last_active': time.time(),
                        'captcha_solved': True,
                'last_message_time': 0,
                        'last_message_time': 0
                    }
                add_group_message('System', f"{session['username']} reconnected", 'system')
            elif request.cookies.get('gid') == session_id and request.cookies.get('guser'):
                username_cookie = request.cookies.get('guser')
                with group_chat_lock:
                    group_users[session_id] = {
                        'username': username_cookie,
                        'ip': request.remote_addr,
                        'last_active': time.time(),
                        'captcha_solved': True,
                'last_message_time': 0,
                        'last_message_time': 0
                    }
                add_group_message('System', f"{username_cookie} reconnected", 'system')
                # Also refresh Flask session so future requests have it
                session.permanent = True
                session['session_id'] = session_id
                session['username'] = username_cookie
            else:
                print(f"Session {session_id} not found in group_users and could not be restored")
                return redirect(url_for('group_login'))
        
        username = group_users[session_id]['username']
        print(f"User {username} accessing group chat")
        
        # Update last active time
        with group_chat_lock:
            group_users[session_id]['last_active'] = time.time()
        
        # Process message if provided (only for POST requests to avoid repeat)
        if request.method == 'POST':
            message = request.form.get('m', '').strip()
            recipient = request.form.get('to', '').strip()
            print(f"Processing message from {username}: {message}")
            
            if message and len(message) <= MAX_MESSAGE_LENGTH:
                # Check rate limiting
                if not check_rate_limit(session_id):
                    print(f"Rate limit exceeded for user {username}")
                    return redirect(url_for('group_chat_page', session_id=session_id))
                
                # Check if it's a private message
                if recipient and recipient in get_active_users() and recipient != username:
                    add_group_message(username, message, 'private', recipient)
                    # Also add to sender's private messages so they can see what they sent
                    with group_chat_lock:
                        if username not in private_messages:
                            private_messages[username] = []
                        private_messages[username].append({
                            'time': get_utc_time(),
                            'sender': 'You',
                            'message': message,
                            'recipient': recipient
                        })
                        # Keep only last 50 private messages per user
                        if len(private_messages[username]) > 50:
                            private_messages[username].pop(0)
                else:
                    add_group_message(username, message, 'public')
            
            # If posting via hidden sink iframe, avoid reloading the page so the streaming iframe is not interrupted
            if request.args.get('sink') == '1':
                return "<!DOCTYPE html><html><head><meta charset='utf-8'><title>OK</title></head><body>OK</body></html>"
            # Otherwise, redirect with recipient parameter to preserve dropdown selection
            if recipient:
                return redirect(url_for('group_chat', session_id=session_id, to=recipient))
            else:
                return redirect(url_for('group_chat', session_id=session_id))
        
        # Generate HTML with iframe for updates (preserves input)
        print(f"Generating HTML for {username}")
        selected_recipient = request.args.get('to', '')  # Get recipient from URL parameter
        html_content = generate_group_chat_content(session_id, selected_recipient)
        print(f"HTML generated successfully, length: {len(html_content)}")
        
        return html_content
        
    except Exception as e:
        print(f"Error in group_chat: {e}")
        import traceback
        traceback.print_exc()
        return f"<html><body><h1>Error</h1><p>{e}</p><a href='/group-login'>Back to login</a></body></html>", 500



@app.route('/rchat')
def rchat():
    # Resolve current chat session id
    sid = session.get('session_id') or request.cookies.get('gid')
    if not sid:
        return redirect(url_for('group_login'))

    # If missing in memory (e.g., after restart), attempt to restore
    if sid not in group_users:
        uname = session.get('username') or request.cookies.get('guser')
        if not uname:
            return redirect(url_for('group_login'))
        with group_chat_lock:
            group_users[sid] = {
                'username': uname,
                'ip': request.remote_addr,
                'last_active': time.time(),
                'captcha_solved': True,
                'last_message_time': 0
            }
        add_group_message('System', f'{uname} reconnected', 'system')

    selected_recipient = request.args.get('to', '')
    return generate_group_chat_content(sid, selected_recipient)

@app.route('/chat-controls/<session_id>', methods=['GET', 'POST'])
def chat_controls(session_id):
    # Ensure session exists or try to restore from Flask session/cookies
    if session_id not in group_users:
        if session.get('session_id') == session_id and session.get('username'):
            with group_chat_lock:
                group_users[session_id] = {
                    'username': session['username'],
                    'ip': request.remote_addr,
                    'last_active': time.time(),
                    'captcha_solved': True,
                'last_message_time': 0
                }
        elif request.cookies.get('gid') == session_id and request.cookies.get('guser'):
            username_cookie = request.cookies.get('guser')
            with group_chat_lock:
                group_users[session_id] = {
                    'username': username_cookie,
                    'ip': request.remote_addr,
                    'last_active': time.time(),
                    'captcha_solved': True,
                'last_message_time': 0
                }
            session.permanent = True
            session['session_id'] = session_id
            session['username'] = username_cookie
        else:
            return "<!DOCTYPE html><html><body><p>Session expired. <a href='/group-login' target='_top'>Login</a></p></body></html>"

    prune_disconnected_users(grace=5)
    username = group_users[session_id]['username']

    if request.method == 'POST':
        # Aggressively prune on send to update Users dropdown immediately
        prune_disconnected_users(grace=0)
        message = request.form.get('m', '')
        recipient = request.form.get('to', '').strip()

        # Only add message if non-empty and within limit; blank posts still refresh the controls (to update dropdown)
        if message is not None:
            msg = message.strip()
            if msg and len(msg) <= MAX_MESSAGE_LENGTH:
                # Check rate limiting
                if not check_rate_limit(session_id):
                    print(f"Rate limit exceeded for user {username}")
                    if recipient:
                        return redirect(url_for('chat_controls', session_id=session_id, to=recipient), code=303)
                    return redirect(url_for('chat_controls', session_id=session_id), code=303)
                
                if recipient and recipient in get_active_users() and recipient != username:
                    add_group_message(username, msg, 'private', recipient)
                    # Record sender's own PM
                    with group_chat_lock:
                        if username not in private_messages:
                            private_messages[username] = []
                        private_messages[username].append({
                            'time': get_utc_time(),
                            'sender': 'You',
                            'message': msg,
                            'recipient': recipient
                        })
                        if len(private_messages[username]) > 50:
                            private_messages[username].pop(0)
                else:
                    add_group_message(username, msg, 'public')

        # Redirect back to controls to clear input and refresh active users (within this iframe)
        if recipient:
            return redirect(url_for('chat_controls', session_id=session_id, to=recipient), code=303)
        return redirect(url_for('chat_controls', session_id=session_id), code=303)

    # GET: render controls with current active users
    selected_recipient = request.args.get('to', '')
    with group_chat_lock:
        group_users[session_id]['last_active'] = time.time()
    active_users = get_active_users()
    resp = make_response(render_template(
        'chat_controls.html',
        session_id=session_id,
        username=username,
        active_users=active_users,
        user_count=len(active_users),
        selected_recipient=selected_recipient,
        MAX_MESSAGE_LENGTH=MAX_MESSAGE_LENGTH
    ))
    resp.headers['Cache-Control'] = 'no-cache, no-store, must-revalidate'
    resp.headers['Pragma'] = 'no-cache'
    resp.headers['Expires'] = '0'
    return resp


def generate_group_chat_content(session_id, selected_recipient=''):
    """Generate complete group chat HTML content using template."""
    try:
        if session_id not in group_users:
            return render_template('main_group_chat.html', 
                                 error="Invalid session", 
                                 messages=[], 
                                 active_users=[], 
                                 username="", 
                                 selected_recipient="")
        
        user_data = group_users[session_id]
        username = user_data['username']
        
        # Get messages and active users (including private messages)
        with group_chat_lock:
            messages = group_messages[-20:] if group_messages else []  # Only last 20 messages
            user_private_msgs = private_messages.get(username, [])
        
        # Combine public and private messages
        all_messages = list(messages)  # Copy public messages
        for msg in user_private_msgs[-10:]:  # Last 10 private messages
            if 'recipient' in msg:  # This is a sent PM
                all_messages.append({
                    'time': msg['time'],
                    'username': 'You',
                    'message': msg['message'],  
                    'type': 'private_sent',
                    'recipient': msg['recipient']
                })
            else:  # This is a received PM
                all_messages.append({
                    'time': msg['time'],
                    'username': msg['sender'],
                    'message': msg['message'],
                    'type': 'private_received'
                })
        
        # Sort by time and limit
        all_messages.sort(key=lambda x: x.get('time', '00:00'))
        recent_messages = all_messages[-30:]  # Keep last 30 combined messages
        
        active_users = get_active_users()
        
        # Use template instead of inline HTML
        return render_template('main_group_chat.html',
                             session_id=session_id,
                             username=username,
                             messages=recent_messages,
                             active_users=active_users,
                             selected_recipient=selected_recipient,
                             user_count=len(active_users))
        
    except Exception as e:
        print(f"Error in generate_group_chat_content: {e}")
        return render_template('main_group_chat.html', 
                             error=f"Error: {str(e)}", 
                             messages=[], 
                             active_users=[], 
                             username="", 
                             selected_recipient="")

def stream_group_chat_content_old(session_id):
    """Stream group chat content with updates."""
    if session_id not in group_users:
        yield "<!DOCTYPE html><html><body>Invalid session. <a href='/group-login'>Login again</a></body></html>"
        return
    
    user_data = group_users[session_id]
    username = user_data['username']
    
    # Yield initial HTML
    yield '<!DOCTYPE html>\n<html lang="en">\n<head>\n'
    yield '''    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <meta http-equiv="refresh" content="3">
    <title>Group Chat - Ableonion</title>
    <link rel="icon" href="data:,">
    <base target="_blank">
'''
    
    # Exact Ableonion styling to match the original
    yield '''    <style>
        html, body {
            background: #c0c0c0;
            color: #000;
            font-family: sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
        body {
            padding: 5px;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 10px);
        }
        .header {
            background: #c0c0c0;
            padding: 2px 0;
            text-align: left;
            border-bottom: 1px solid #808080;
            margin-bottom: 3px;
        }
        .header h1 {
            color: #000;
            margin: 0;
            font-size: 11px;
            font-weight: normal;
            font-family: sans-serif;
        }
        .userinfo {
            color: #000;
            font-size: 10px;
            margin-bottom: 2px;
        }
        .users-online {
            background: #fff;
            border: 1px inset #c0c0c0;
            padding: 2px 4px;
            margin-bottom: 3px;
            font-size: 10px;
            color: #000;
            height: 40px;
            overflow-y: auto;
        }
        .chat-area {
            background: #fff;
            border: 1px inset #c0c0c0;
            flex: 1;
            overflow-y: auto;
            padding: 2px 4px;
            margin-bottom: 3px;
            font-size: 10px;
            line-height: 1.2;
        }
        .message {
            margin: 1px 0;
            font-size: 10px;
            word-wrap: break-word;
        }
        .message .time {
            color: #000;
            font-size: 10px;
        }
        .message .username {
            color: #000;
            font-weight: normal;
        }
        .message .private-msg {
            color: #800080;
            font-style: italic;
        }
        .message .text {
            color: #000;
        }
        .input-area {
            background: #c0c0c0;
            padding: 2px;
            display: flex;
            gap: 2px;
        }
        .input-area form {
            display: flex;
            gap: 2px;
            width: 100%;
        }
        .input-area input[type="text"] {
            flex: 1;
            background: #fff;
            border: 1px inset #c0c0c0;
            color: #000;
            padding: 1px 3px;
            font-family: sans-serif;
            font-size: 10px;
            height: 16px;
        }
        .input-area input[type="text"]:focus {
            outline: none;
        }
        .input-area button {
            background: #c0c0c0;
            border: 1px outset #c0c0c0;
            color: #000;
            padding: 1px 8px;
            font-family: sans-serif;
            font-size: 10px;
            cursor: pointer;
            height: 20px;
        }
        .input-area button:active {
            border: 1px inset #c0c0c0;
        }
        .nav-links {
            text-align: center;
            margin-top: 2px;
            font-size: 9px;
        }
        .nav-links a {
            color: #000080;
            text-decoration: underline;
            margin: 0 5px;
        }
        .nav-links a:hover {
            color: #800080;
        }
    </style>
'''
    yield '</head>\n<body>\n'
    
    # Header
    yield '''    <div class="header">
        <h1>Ableonion Group Chat</h1>
    </div>
'''
    
    # User info
    yield f'    <div class="userinfo">Logged in as: {escape(username)}</div>\n'
    
    # Active users
    active_users = get_active_users()
    users_text = f"Users online ({len(active_users)}): " + ", ".join(active_users)
    yield f'    <div class="users-online">{escape(users_text)}</div>\n'
    
    # Chat area
    yield '    <div class="chat-area" id="messages">\n'
    
    # Get and display messages
    with group_chat_lock:
        messages = group_messages.copy()
        user_private_msgs = private_messages.get(username, [])
    
    # Combine and sort messages by time
    all_messages = []
    for msg in messages:
        all_messages.append(msg)
    
    for msg in user_private_msgs:
        all_messages.append({
            'time': msg['time'],
            'username': msg['sender'],
            'message': msg['message'],
            'type': 'private_received'
        })
    
    # Sort by time (newest first for display)
    all_messages.sort(key=lambda x: x['time'], reverse=True)
    
    # Display messages in chronological order (oldest first)
    for msg in reversed(all_messages[-50:]):  # Show last 50 messages, oldest first
        yield '        <div class="message">\n'
        yield f'            <span class="time">[{msg["time"]}]</span> '
        
        if msg['type'] == 'private_received':
            yield f'<span class="private-msg">[PM from {escape(msg["username"])}]: {escape(msg["message"])}</span>'
        elif msg['type'] == 'private':
            yield f'<span class="private-msg">[PM to you]: {escape(msg["message"])}</span>'
        else:
            yield f'<span class="username">{escape(msg["username"])}</span>: '
            yield f'<span class="text">{escape(msg["message"])}</span>'
        
        yield '        </div>\n'
    
    yield '    </div>\n'
    
    # Input area
    yield f'''    <div class="input-area">
        <form method="post">
            <input type="text" name="m" placeholder="Type your message..." maxlength="{MAX_MESSAGE_LENGTH}" autofocus>
            <button type="submit">Send</button>
        </form>
    </div>
'''
    
    # Navigation
    yield '''    <div class="nav-links">
        <a href="/group-login">Logout</a> |
        <a href="/">Home</a> |
        <a href="/help">Help</a>
    </div>
'''
    
    
    yield '</body>\n</html>\n'

@app.route('/')
def home():
    return render_template('landing.html')

@app.route('/debug')
def debug():
    return f"<h1>Debug Info</h1><p>Active users: {len(group_users)}</p><p>Messages: {len(group_messages)}</p>"

@app.route('/chat-messages/<session_id>')
def group_chat_messages(session_id):
    """Streaming iframe that appends messages without reloading (no JavaScript)"""
    if session_id not in group_users:
        # Try to restore from Flask session cookie (handles multi-worker deployments)
        if session.get('session_id') == session_id and session.get('username'):
            with group_chat_lock:
                group_users[session_id] = {
                    'username': session['username'],
                    'ip': request.remote_addr,
                    'last_active': time.time(),
                    'captcha_solved': True,
                'last_message_time': 0
                }
        elif request.cookies.get('gid') == session_id and request.cookies.get('guser'):
            username_cookie = request.cookies.get('guser')
            with group_chat_lock:
                group_users[session_id] = {
                    'username': username_cookie,
                    'ip': request.remote_addr,
                    'last_active': time.time(),
                    'captcha_solved': True,
                'last_message_time': 0
                }
            # Also refresh Flask session cookie for consistency
            session.permanent = True
            session['session_id'] = session_id
            session['username'] = username_cookie
        else:
            return "<html><body>Session expired</body></html>"

    username = group_users[session_id]['username']

    # Register active streaming connection
    with group_chat_lock:
        conn = active_group_connections.get(session_id)
        if not conn:
            active_group_connections[session_id] = {'count': 1, 'timestamp': time.time()}
        else:
            conn['count'] += 1
            conn['timestamp'] = time.time()

    def event_stream():
        # Initial skeleton (no meta refresh; keep connection open)
        yield """<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        html, body {
            background: #c9d9b7;
            color: #000;
            font-family: monospace;
            font-size: 16px;
            margin: 0;
            padding: 10px;
            height: 100%;
            overflow-y: auto;
        }
        .user-info {
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }
        .welcome-msg {
            margin: 0;
            padding: 0;
            line-height: normal;
        }
        .welcome-nick {
            color: #00ff00;
            font-weight: bold;
        }
        .welcome-title {
            color: #0000ff;
            font-weight: bold;
        }
        .welcome-topic {
            color: #ff0000;
            font-weight: normal;
        }
        .welcome-freedom {
            color: #0000ff;
            font-weight: normal;
        }
        .welcome-private {
            color: #ff8800;
            font-weight: bold;
        }
        .welcome-command {
            color: #00ff00;
            font-weight: normal;
        }
        .timestamp {
            color: #000;
            font-weight: normal;
        }
        .username {
            font-weight: bold;
            color: #000;
        }
        .system-msg {
            color: #0000ff;
            font-weight: normal;
        }
        .private-msg {
            color: #800080;
        }
    </style>
</head>
<body>
"""
        # Static header part
        # Send padding to defeat initial buffering and render immediately in some browsers/tor setups
        yield '<!-- padding ' + (' ' * 16384) + ' -->\n'
        yield f'    <div class="user-info">Logged in as: <strong>{escape(username)}</strong></div>\n'
        yield f'    <div class="user-info">Users online: {len(get_active_users())}</div>\n'
        yield f'    <div class="welcome-msg">Hello <span class="welcome-nick">{escape(username)}</span>! Welcome to <span class="welcome-title">Ableonion Chat.</span></div>\n'
        yield '    <div class="welcome-msg"><span class="welcome-topic">The topic is general discussion with</span> <span class="welcome-freedom">freedom of speech.</span></div>\n'
        yield '    <div class="welcome-msg">Refresh the list of current users for <span class="welcome-private">private messages</span> by sending an empty message.</div>\n'
        yield '    <div class="welcome-msg">Use command <span class="welcome-command">/ignore &lt;user&gt;</span> to hide all messages from a particular user.</div>\n'
        yield '    <hr>\n'

        # Initial backlog
        with group_chat_lock:
            recent_public = group_messages[-30:] if group_messages else []
            user_private_msgs = private_messages.get(username, [])
            recent_private = user_private_msgs[-10:] if user_private_msgs else []
            last_public_idx = len(group_messages)
            last_private_idx = len(user_private_msgs) if user_private_msgs else 0
            # Mark active time
            group_users[session_id]['last_active'] = time.time()

        # Render initial combined messages (approximate chronological without JS)
        # We render public first then the last private messages
        for msg in recent_public:
            yield f'    <span class="timestamp">[{msg.get("time", "00:00")}]</span> '
            if msg.get("type") == "system":
                yield f'<span class="system-msg">* {escape(msg.get("message", ""))}</span><br>\n'
            else:
                yield f'<span class="username">{escape(msg.get("username", ""))}</span>: {escape(msg.get("message", ""))}<br>\n'
        for msg in recent_private:
            yield f'    <span class="timestamp">[{msg.get("time", "00:00")}]</span> '
            if 'recipient' in msg:
                yield f'<span class="private-msg">[PM to {escape(msg.get("recipient", ""))}]: {escape(msg.get("message", ""))}</span><br>\n'
            else:
                yield f'<span class="private-msg">[PM from {escape(msg.get("sender", ""))}]: {escape(msg.get("message", ""))}</span><br>\n'

        # Continuous incremental updates
        while True:
            with group_chat_lock:
                # Reset indices if lists were trimmed
                if last_public_idx > len(group_messages):
                    last_public_idx = len(group_messages)
                current_priv_list = private_messages.get(username, [])
                if current_priv_list is None:
                    current_priv_list = []
                if last_private_idx > len(current_priv_list):
                    last_private_idx = len(current_priv_list)

                new_public = group_messages[last_public_idx:]
                new_private = current_priv_list[last_private_idx:]
                last_public_idx = len(group_messages)
                last_private_idx = len(current_priv_list)
                # Update last active
                if session_id in group_users:
                    group_users[session_id]['last_active'] = time.time()
                # Heartbeat the connection so stale entries age out if the stream dies
                conn = active_group_connections.get(session_id)
                if conn:
                    conn['timestamp'] = time.time()

            # Emit new public messages
            for msg in new_public:
                yield f'    <span class="timestamp">[{msg.get("time", "00:00")}]</span> '
                if msg.get("type") == "system":
                    yield f'<span class="system-msg">* {escape(msg.get("message", ""))}</span><br>\n'
                else:
                    yield f'<span class="username">{escape(msg.get("username", ""))}</span>: {escape(msg.get("message", ""))}<br>\n'

            # Emit new private messages (received)
            for msg in new_private:
                if 'recipient' in msg:
                    # Sent PMs show as "to <recipient>"
                    yield f'    <span class="timestamp">[{msg.get("time", "00:00")}]</span> '
                    yield f'<span class="private-msg">[PM to {escape(msg.get("recipient", ""))}]: {escape(msg.get("message", ""))}</span><br>\n'
                else:
                    yield f'    <span class="timestamp">[{msg.get("time", "00:00")}]</span> '
                    yield f'<span class="private-msg">[PM from {escape(msg.get("sender", ""))}]: {escape(msg.get("message", ""))}</span><br>\n'

            # Keep-alive to prevent proxies from closing the stream
            yield f'<!-- keepalive {int(time.time())} -->\n'
            time.sleep(1)

    # Return long-lived streaming response
    resp = Response(
        event_stream(),
        mimetype='text/html',
        headers={
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0',
            'X-Accel-Buffering': 'no',
            'Connection': 'keep-alive'
        }
    )

    @resp.call_on_close
    def _cleanup_connection():
        uname = None
        remove_user = False
        with group_chat_lock:
            conn = active_group_connections.get(session_id)
            if conn:
                conn['count'] -= 1
                conn['timestamp'] = time.time()
                if conn['count'] <= 0:
                    active_group_connections.pop(session_id, None)
                    if session_id in group_users:
                        uname = group_users[session_id]['username']
                        # Remove user to avoid stale entries in dropdown counts
                        group_users.pop(session_id, None)
                        private_messages.pop(uname, None)
                        remove_user = True
        if remove_user and uname:
            add_group_message('System', f'{uname} left the chat', 'system')

    return resp


@app.route('/links')
def links():
    return render_template('links.html')

@app.route('/help')
def help():
    return render_template('help.html')

@app.route('/contact/', methods=['GET', 'POST'])
def contact():
    if request.method == 'POST':
        message = request.form.get('message', '').strip()
        message = sanitize_input(message, MAX_MESSAGE_LENGTH)
        if message and len(message) <= MAX_MESSAGE_LENGTH:
            # Secure file handling - no user input in filename
            feedback_file = os.path.join(os.path.dirname(__file__), 'feedback.txt')
            try:
                with open(feedback_file, 'a', encoding='utf-8') as f:
                    timestamp = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                    f.write(f'[{timestamp}] Message: {escape(message)}\n---\n')
            except Exception as e:
                logging.error(f"Error writing feedback: {e}")
        return render_template('thank_you.html')
    return render_template('contact.html')



# Run cleanup function periodically
def run_cleanup():
    while True:
        time.sleep(60)  # Run every minute
        cleanup_inactive_group_users()

if __name__ == '__main__':
    # Start cleanup thread
    cleanup_thread = threading.Thread(target=run_cleanup, daemon=True)
    cleanup_thread.start()
    
    # Run the Flask app
    # Note: Using threaded=True is essential for streaming responses
    # Additional security measures
    @app.errorhandler(404)
    def not_found(error):
        """Custom 404 handler to prevent information disclosure."""
        return make_response("Page not found", 404)

    @app.errorhandler(500)
    def internal_error(error):
        """Custom 500 handler to prevent error information disclosure."""
        logging.error(f"Internal server error: {error}")
        return make_response("Internal server error", 500)

    @app.errorhandler(429)
    def rate_limited(error):
        """Custom rate limit handler."""
        return make_response("Rate limited - too many requests", 429)
    
    app.run(host='127.0.0.1', port=5000, debug=False, threaded=True)
