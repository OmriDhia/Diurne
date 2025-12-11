FROM node:20-alpine

# Install git for expo-cli and other tools that might need it
RUN apk add --no-cache git bash

WORKDIR /app

# Install global dependencies
# expo-cli is now part of local dependencies usually, but installing expo-cli globally *can* be useful for some utilities, 
# though 'npx expo' is preferred. We'll stick to local deps mostly, but lets ensure we have robust tools.

# Copy package files first to leverage cache
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy source code
COPY . .

# Expose ports for Expo and Metro Bundler
# 8081: Metro Bundler
# 19000: Expo Go
# 19001/19002: Debugging/Other
EXPOSE 8082 19000 19001 19002

# The default command will be overridden by docker-compose, but good to have a sensible default
CMD ["npx", "expo", "start"]
