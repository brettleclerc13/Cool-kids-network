FROM debian:buster

#dependencies
RUN apt update -y
RUN apt upgrade -y
RUN apt-get install mariadb-server -y
#RUN apt-get install bash -y

COPY conf/50-server.cnf /etc/mysql/mariadb.conf.d/50-server.cnf

COPY tools/mysql.sh /

#below action only required if not using bash
RUN chmod +x /mysql.sh

CMD ["./mysql.sh"]

