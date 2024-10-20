__Problem Statement__

The task was to develop a proof of concept for a new game called the Cool Kids Network, which is essentially a platform where users can sign up, generate their characters, and view their data based on different roles. The challenge was to create a user-management system that allows for different levels of data access, with specific functionality based on user roles like "Cool Kid," "Cooler Kid," and "Coolest Kid."


__Technical Specifications__

I decided to go with a Docker environment since it provides a consistent and isolated development environment.
- It works across various environments. This eliminates the classic "it works on my machine" problem.
- With the provided Makefile, setting up the entire application is as simple as running a single command (make). Reduces setup time.

Once the environment setup and a basic empty wordpress website was created, I then decided to work with the newer WP block-theme approach to website buliding.
To be honest, it was new for me since I worked on previous projects relied on the classic builder where pages can be built form the ground up.
The new markup language and the approach to coding blocks and patterns presented a challenge. I used my custom pattern for the hero section and then resorted to short_codes. The issue with patterns I feel is that they needs to be as bare bone as possible because it gets cached in WP, so instant changes do not reflect on the frontend.
Anyway, it was a decent leanring curve, now dealing with block-themes is a breeze.

The system is observable, resilient and can easily be monitored..
Observable: The adminer dashboard is a fine way of quickly accessing the databases in case of any issues. The src/Volumes directory, thanks to docker, is bridged to the actual website. So all files are viewable and quickly accessible. Any modifications will be instantly reflected on the actual website.
Resilient: Security measures have been taken to ensure that a normal user cannot access the wp-admin panel, usually through a simple redirection after checking their role status.
Easily be monitored: I opted out of a Prometheus monitoring service because I feel it would have been an overkill. Instead the Query Monitor plugin does a nice job of reporting. Also, the WP_DEBUG options are usual the best way to resolve bugs and crashes.

The API endpoint for changing a user-role was achieved by an add_filter function to show the Application Passwords menu in the user profile section, despite the website running on HTTP and not HTTPS. That is one regret, of not having started off the project using a self-signed certificate.

A PHP Code Sniffer lint inspection was carried out, excluding the WP core files and other files not directly related to the website.
That was a challenge as well because there were a lot of functions that I used were not as per the WP standards. My WP forms were not checking for wp_nonces, so I then needed to change to a simple HTML form injected in the pages through shortcodes. Linting has really helped me better understand coding standards and keep up to date with the functions to use. Another regret is not implementing it at the start of the project.

For exact details on how to operate the system, please view the readme file.


__Conclusion__

This project has not only deepened my understanding of WordPress and its newer block theme approach but also enhanced my skills in building user management systems. The challenges I faced, from adapting to the new markup language to implementing security measures and ensuring code quality through linting, have all contributed to my growth as a developer.

I look forward to continuing this collaborative methodology within a team that appears to be at its best game!
