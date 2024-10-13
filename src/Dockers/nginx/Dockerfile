FROM alpine:3.19

#dependencies
RUN apk upgrade
RUN apk add nginx
RUN apk add curl
RUN apk add vim

#nginx conf
RUN mkdir -p /var/run/nginx
COPY conf/nginx.conf /etc/nginx/nginx.conf

CMD ["nginx", "-g", "daemon off;"]
