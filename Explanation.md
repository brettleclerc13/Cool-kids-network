__Problem Statement__

The task was to develop a proof of concept for a new game called the Cool Kids Network, which is essentially a platform where users can sign up, generate their characters, and view their data based on different roles. The challenge was to create a user-management system that allows for different levels of data access, with specific functionality based on user roles like "Cool Kid," "Cooler Kid," and "Coolest Kid".


__Technical Specifications__

I decided to go with a Docker environment since it provides a consistent and isolated development environment.
- It works across various environments. This eliminates the classic "it works on my machine" problem.
- With the provided Makefile, setting up the entire application is as simple as running a single command (make). Reduces setup time.

Once the environment was setup and a basic empty wordpress website was created, I then decided to work with the newer WP block-theme approach to website buliding.
To be honest, it was new for me since I have worked on previous projects that rely on the classic builder.
The WP markup language and the approach to coding blocks and patterns presented a challenge. I used my custom pattern for the hero section and then resorted to short_codes for other use cases. The issue with patterns I feel is that they needs to be as bare bone as possible because once added to a page, additions or styling changings do not reflect instantly.
Anyway, it was a decent learning curve. Dealing with block-themes is now a breeze.

__The system is observable, resilient and can easily be monitored..__
- Observable: The adminer dashboard (a phpMyAdmin alternative) is a fine way to quickly access the WP databases in case of any issues.
The src/Volumes directory, thanks to docker network, is bridged to the actual website. So all files linked to the website are quickly accessible. Any modifications will reflect instantly on the website.
- Resilient: Security measures have been taken to ensure that a normal user cannot access the wp-admin panel, usually through a simple redirection after checking their role status.
- Easily monitored: I opted out of a Prometheus monitoring service because I felt that it would have been an overkill for this project. Instead, the Query Monitor plugin does a nice job of reporting. Also, the simple but effective WP_DEBUG logs are usually the best way to resolve bugs and crashes.

__User Stories 1 to 4__

Since the user stories were precise, the approach was straightforward. The buttons present on the homepage carry out the task of navigating the new/existing user to the user dashboard. I added a feature to automatically log in the user to the user dashboard after signing up, for a better user experience.

__User Story 5__

The API endpoint to change a user's role was achieved with the Applications Password feature available in the user profile section. Despite the website running on the HTTP protocol and not HTTPS, I managed after a while to make use of the Applications Password feature through an add_filter function to override the lock. Otherwise, the feature was not available. One of the regrets that I have is not having started off the project using the HTTPS protocol through a self-signed certificate (TSL).

__Lint Inspection__

A PHP Code Sniffer lint inspection was carried out, excluding the WP core files and other files not directly related to the website.
That was a challenge as well because there were a couple of functions that I used that were not at par with the current WP standards.
For example, my WP forms were not verifying wp_nonce keys, so I then resorted to a simple HTML form inserted in the login/signup pages through shortcodes.
The lint inspection has really helped me better understand coding standards and keep up to date with the new or deprecated functions. Another regret I have is not having implemented these checks at the start of the project.

I unfortunately did not find a use-case for the wpm filters, but I look forward to implementing them in my code from now onwards.

For exact details on how to operate the system, please view the readme file.

__Conclusion__

This project has not only deepened my understanding of WordPress and its newer block theme approach but also enhanced my skills in building user management systems. The challenges I faced, from adapting to the new markup language to ensuring code quality through linting, have all contributed to my growth as a developer.

I look forward to continuing this structured approach to coding within a team that appears to be at its best!
