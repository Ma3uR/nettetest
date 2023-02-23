ARG MYSQL_VERSION

FROM mysql:${MYSQL_VERSION}

## User configuration
ARG APPLICATION_USER_ID
ARG APPLICATION_USER_GROUP
RUN echo "mysql-container:x:$APPLICATION_USER_ID:$APPLICATION_USER_GROUP:container,,,:/:/bin/bash" >> /etc/passwd
RUN echo "mysql-container:x:$APPLICATION_USER_GROUP:" >> /etc/group
