<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## About Link Harvester Project

Link Harvester is a simple app which collects links from users. Any user can submit links which are validated and stored by the application. Users can see the submitted (links/domains) and search, sort those data. The results are displayed in a paginated table.

### Technologies used for the development
Here's the required technologies are used for the project
- PHP v8.2
- Laravel v10
- Alpine Js v3.x
- jQuery v3.7
- Bootstrap v5
- jQuery Datatable v1.36
---
**The running environment tools or libs**
- Docker v4.2
- Supervisor v4.x

## Installation Process

###Prerequisites 
Before you begin, ensure you have the following prerequisites installed on your system:
[Docker](https://www.docker.com/ "Docker")
[Docker Compose](https://docs.docker.com/compose/ "Docker Compose")
Git (Optional for cloning the repository)


###Clone the Repository
If you haven't already, you can clone this repository using Git:
- `git clone https://github.com/misujon/link-hervester-app.git`

- `cd link-hervester-app`


###Docker Setup

This project uses Docker for easy setup. Follow these steps to get the application up and running:

Build the Docker containers:
- `docker-compose build`

- `docker-compose up -d`


###Setting Up Supervisor for Laravel Workers

Laravel provides a powerful job processing system called "queues," which can be managed using Supervisor. Here's how to set up Supervisor for Laravel workers within the Docker environment:

Enter the workspace container:
- `docker-compose exec app bash`

Copy the laravel-worker.conf file to the Supervisor config folder:
- `cp /var/www/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf`

Now if there's no supervisor installed follow those steps otherwise skip.
- `apt-get update`
- `apt-get install -y supervisor`

Instruct Supervisor to read and update its configuration to include the new .**conf** file:
- `supervisorctl reread`
- `supervisorctl update`

Start the Laravel worker process
- `supervisorctl start laravel-worker:*`

That's it! Supervisor is now set up to manage your Laravel worker processes.


###Project Starting Point

After successfully installing the project now the project can be accessible here on **8000** port with **localhost**
- `http://localhost:800`

For more easier to access database here's the adminer package also included. To check the database need to go here.
- `http://localhost:8080`

Here's the default DB credentials. Those can be changeable as per the configuration.
- `username: root`
- `password: mysecretpassword`


##Project Workflow

1. On home page all the domains are fetch through datatable with the soring and searching options. Here all the domains will be showing with the **Domain links** button. For each domain has some links stored to the links table and how many links are there it also mentioned into the table as **Available Links**
![](https://i.ibb.co/xzv32yB/Screenshot-2023-09-15-at-4-38-08-PM.png)

2. There's another page called Add Urls. where an user can add their links. The adding process are based on some criterias are,
![](https://i.ibb.co/zQg0ZCv/Screenshot-2023-09-15-at-4-50-21-PM.png)
- After submitting the urls first it seperated all the links domains and filter them and keep the unique domains only. 
- Now by linking the domain id all the links are chunked with the particular domain.
- And after processing those insert all the domains and links in a bulk inserting process.
- All those process are completing through the laravel batch jobs feature where all the links data chunked as 1000 records and executes them to the batch.
- With this process those all the inserting operation is gone to the job queue and the queue has been executed through supervisor and the data are saving throughuly.

3. And finally those links are stored based on their domain. Those can be accessible here. where I fetched the links of domain: **www.sample.com**
![](https://i.ibb.co/xM8kXmQ/Screenshot-2023-09-15-at-4-52-35-PM.png)

***Note: I've stored the domain and subdomains as a single domain, it also can be sorted as only domain.***


##Additional Information

For more information on Laravel and Docker, please refer to the official documentation:

- [Laravel Documentation](https://laravel.com/docs "Laravel Documentation")
- [Docker Documentation](https://docs.docker.com/ "Docker Documentation")

This README should provide clear instructions for cloning, installing, and configuring your Laravel application with Docker and Supervisor. Make sure to keep your Laravel-worker.conf file up-to-date and refer to Supervisor's documentation for advanced configuration options.


##Developer Contact

Feel free to ask me anything about the project or if there's having any issue. Here's I'm available with **whatsapp/telegram**

###+8801676707067


###Thank you