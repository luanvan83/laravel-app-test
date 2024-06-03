#!/bin/sh

nodeModuleDir="node_modules"
if [ ! -d "$nodeModuleDir" ]; then
  echo "$nodeModuleDir does not exist. Run npm install"
  npm install --silent
fi

cp .env.sample .env

exec "$@"