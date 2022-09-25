#!/usr/bin/env bash

# Set the TLD domain we want to use
DOMAIN=$DEV_DOMAIN

# Days for the cert to live
DAYS=1095

# A blank passphrase
PASSPHRASE=""

# Generated configuration file
CA_CONFIG_FILE="config.txt"

# The file name can be anything
FILE_NAME="ssl-cert"

cat > $CA_CONFIG_FILE <<-EOF
[req]
prompt = no
distinguished_name = dn

[dn]
C = GB
ST = London
L = London
O = ITRM
OU = WDD
CN = $DEV_DOMAIN
EOF

#if test -f "ssl-cert-*" -a -f "myCA.*"; then
echo "Generating root cert"
openssl genrsa -out myCA.key 2048
openssl req -x509 -new -nodes -key myCA.key -sha256 -days $DAYS -out myCA.pem -config "$CA_CONFIG_FILE"
echo "Generating certs for $DOMAIN"
openssl genrsa -out $FILE_NAME.key 2048
openssl req -new -key $FILE_NAME.key -out $FILE_NAME.csr -config "$CA_CONFIG_FILE"

>$FILE_NAME.ext cat <<-EOF
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
subjectAltName = @alt_names
[alt_names]
DNS.1 = $DOMAIN # Be sure to include the domain name here because Common Name is not so commonly honoured by itself
DNS.2 = bar.$DOMAIN # Optionally, add additional domains (I've added a subdomain here)
IP.1 = 192.168.0.13 # Optionally, add an IP address (if the connection which you have planned requires it)
EOF

openssl x509 -req -in $FILE_NAME.csr -CA myCA.pem -CAkey myCA.key -CAcreateserial \
-out $FILE_NAME.crt -days $DAYS -sha256 -extfile $FILE_NAME.ext
#fi



# Remove previous keys
# echo "Removing existing certs like $FILE_NAME.*"
# chmod 770 $FILE_NAME.*
# rm $FILE_NAME.*


# Protect the key
# chmod 400 "$FILE_NAME.key"
