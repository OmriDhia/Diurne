#!/bin/bash

# Configuration
API_URL="http://localhost:8001/api/mobile"
EMAIL="admin@diurne.com"
PASSWORD="12345678"

echo "=== Mobile API Test Script ==="
echo "Target: $API_URL"

# 1. Authenticate
echo -e "\n1. Authenticating..."
AUTH_RESPONSE=$(curl -s -X POST "$API_URL/authenticate" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\", \"password\":\"$PASSWORD\"}")

echo "Response: $AUTH_RESPONSE"

# Extract Token (Simple regex approach to avoid jq dependency)
TOKEN=$(echo $AUTH_RESPONSE | grep -o '"token":"[^"]*' | grep -o '[^"]*$')

if [ -z "$TOKEN" ]; then
  echo "Error: Failed to get token."
  exit 1
fi

echo "Token received: ${TOKEN:0:20}..."

# 2. Create Permission
echo -e "\n2. Creating Permission (TestPerm)..."
PERM_RESPONSE=$(curl -s -X POST "$API_URL/permissions" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"TestPerm", "description":"Created via test script"}')
echo $PERM_RESPONSE

PERM_ID=$(echo $PERM_RESPONSE | grep -o '"id":[^,]*' | awk -F: '{print $2}')
echo "Perm ID: $PERM_ID"

# 3. Get Permissions
echo -e "\n3. Getting Permissions..."
curl -s -X GET "$API_URL/permissions" -H "Authorization: Bearer $TOKEN" | head -c 200
echo "..."

# 4. Update Permission
echo -e "\n4. Updating Permission ID $PERM_ID..."
curl -s -X PUT "$API_URL/permissions/$PERM_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"description":"Updated description"}'

# 5. Create User
echo -e "\n5. Creating User (testuser@diurne.com)..."
USER_RESPONSE=$(curl -s -X POST "$API_URL/users" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"TestUser\", \"email\":\"testuser_$PERM_ID@diurne.com\", \"password\":\"password\", \"permissionId\":$PERM_ID, \"isActive\":true}")
echo $USER_RESPONSE

USER_ID=$(echo $USER_RESPONSE | grep -o '"id":[^,]*' | awk -F: '{print $2}')
echo "User ID: $USER_ID"

# 6. Get User (Single)
echo -e "\n6. Getting User ID $USER_ID..."
curl -s -X GET "$API_URL/users/$USER_ID" -H "Authorization: Bearer $TOKEN"

# 6b. Get Users (List)
echo -e "\n6b. Getting All Users..."
curl -s -X GET "$API_URL/users" -H "Authorization: Bearer $TOKEN" | head -c 200
echo "..."


# 7. Update User
echo -e "\n7. Updating User ID $USER_ID..."
curl -s -X PUT "$API_URL/users/$USER_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"UpdatedTestUser"}'

# 8. Delete User
echo -e "\n8. Deleting User ID $USER_ID..."
curl -s -X DELETE "$API_URL/users/$USER_ID" -H "Authorization: Bearer $TOKEN"

# 9. Delete Permission
echo -e "\n9. Deleting Permission ID $PERM_ID..."
curl -s -X DELETE "$API_URL/permissions/$PERM_ID" -H "Authorization: Bearer $TOKEN"

echo -e "\n\n=== Test Complete ==="
