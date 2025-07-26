const https = require('https');

const apiKey = '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';

const options = {
  hostname: 'openrouter.ai',
  path: '/api/v1/auth/key',
  method: 'GET',
  headers: {
    'Authorization': `Bearer ${apiKey}`,
    'Content-Type': 'application/json'
  }
};

const req = https.request(options, (res) => {
  let data = '';

  res.on('data', (chunk) => {
    data += chunk;
  });

  res.on('end', () => {
    try {
      const response = JSON.parse(data);
      console.log('OpenRouter API Key Information:');
      console.log('----------------------------');
      
      if (response.error) {
        console.log(`Error: ${response.error.message || response.error}`);
      } else {
        console.log(`Rate Limit: $${response.rate_limit_usd} per minute`);
        console.log(`Credits Remaining: $${response.credit_balance_usd}`);
        console.log(`Tier: ${response.tier}`);
      }
    } catch (e) {
      console.error('Error parsing response:', e.message);
      console.log('Raw response:', data);
    }
  });
});

req.on('error', (error) => {
  console.error('Error checking API key:', error.message);
});

req.end();
