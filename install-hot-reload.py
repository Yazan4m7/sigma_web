import json
import os
import subprocess

# Ensure package.json exists
if not os.path.exists("package.json"):
    print("No package.json found. Creating one...")
    subprocess.run("npm init -y", shell=True)

# Install live-server and nodemon
print("📦 Installing live-server and nodemon...")
subprocess.run("npm install -D live-server nodemon", shell=True)

# Read and update package.json
with open("package.json", "r+", encoding="utf-8") as f:
    package = json.load(f)

    scripts = package.get("scripts", {})
    scripts["serve"] = "live-server public --watch=public --open=public"
    scripts["reload-blade"] = "nodemon --ext blade.php --exec \"touch public/index.html\""
    package["scripts"] = scripts

    f.seek(0)
    json.dump(package, f, indent=2)
    f.truncate()

print("\n✅ Live reload setup complete!")
print("Run these in separate terminals to test it out:")
print("👉 npm run serve        # opens live server watching /public")
print("👉 npm run reload-blade # triggers browser reload on blade changes")