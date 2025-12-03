const fetch = require('node-fetch');
require('dotenv').config();

// Try to get API key from environment variable or .env file first
let apiKey = process.env.OPENROUTER_API_KEY;

// If not found, use the hardcoded key
if (!apiKey) {
  apiKey = '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';
  console.log('Using hardcoded API key');
}

console.log(`Checking API key: ${apiKey.substring(0, 10)}...`);

async function checkCredits() {
  try {
    const response = await fetch('https://openrouter.ai/api/v1/auth/key', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${apiKey}`,
        'Content-Type': 'application/json'
      }
    });

    console.log(`Status Code: ${response.status}`);
    
    const data = await response.text();
    console.log('Raw response:', data);
    
    try {
      const jsonData = JSON.parse(data);
      console.log('\nOpenRouter API Key Information:');
      console.log('----------------------------');
      
      if (jsonData.error) {
        console.log(`Error: ${jsonData.error.message || JSON.stringify(jsonData.error)}`);
      } else {
        console.log('Full response object:');
        console.log(JSON.stringify(jsonData, null, 2));
        
        // Try different possible response structures
        const creditInfo = jsonData.data || jsonData;
        
        console.log(`Rate Limit: $${creditInfo.rate_limit_usd || 'N/A'} per minute`);
        console.log(`Credits Remaining: $${creditInfo.credit_balance_usd || 'N/A'}`);
        console.log(`Tier: ${creditInfo.tier || 'N/A'}`);
        
        // Try additional fields that might contain credit information
        if (creditInfo.balance) {
          console.log(`Balance: $${creditInfo.balance}`);
        }
        if (creditInfo.credit) {
          console.log(`Credit: $${creditInfo.credit}`);
        }
      }
    } catch (e) {
      console.error('Error parsing JSON:', e.message);
    }
  } catch (error) {
    console.error('Error making request:', error.message);
  }
}

checkCredits();
