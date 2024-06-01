FROM node:22-slim
RUN mkdir -p /opt/app
WORKDIR /opt/app
COPY ./front/package.json .
#COPY ./front/package-lock.json .
RUN npm install
COPY ./front/ .
EXPOSE 3000
CMD [ "npm", "run", "dev"]