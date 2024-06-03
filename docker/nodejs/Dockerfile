FROM node:22-slim

ARG SERVICE_NAME

COPY ./docker/nodejs/node-entrypoint.sh /node-entrypoint.sh
RUN chmod u+x /node-entrypoint.sh

RUN mkdir -p /opt/app
WORKDIR /opt/app

COPY ./${SERVICE_NAME}/ .

# RUN npm install

ENTRYPOINT ["/node-entrypoint.sh"]

EXPOSE 3000
CMD [ "npm", "run", "dev"]