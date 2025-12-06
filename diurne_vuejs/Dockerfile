FROM node:lts-alpine

WORKDIR /app

# Install dependencies (cached if package.json doesn't change)
COPY package*.json ./
RUN npm install

# In dev, we mount the volume, so we don't strictly need COPY . . 
# but we do it for the initial layer or if volume isn't mounted.
COPY . .

EXPOSE 8080 5173

# Start Vite dev server with host exposure
CMD ["npm", "run", "dev", "--", "--host"]