    # Use an official MySQL runtime as the base image
    FROM mysql:8.2.0

    # Copy the SQL initialization script into the container
    COPY dump.sql /docker-entrypoint-initdb.d/
    COPY my.cnf /etc/mysql/
    RUN chmod 644 /etc/mysql/my.cnf

    # Expose port 3306 for MySQL connections
    EXPOSE 3306

    # Set environment variables for MySQL configuration
    ENV MYSQL_ROOT_PASSWORD=rootpasswd2341
    ENV MYSQL_DATABASE=boncard
    ENV MYSQL_USER=boncarduser
    ENV MYSQL_PASSWORD=passwd2341

    RUN mkdir -p /var/lib/mysql-files \
        && chmod 750 /var/lib/mysql-files \
        && chown mysql:mysql /var/lib/mysql-files

    # Start MySQL
    CMD ["mysqld"]