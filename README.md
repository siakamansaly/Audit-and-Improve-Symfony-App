<div id="top"></div>
<div align="right">

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4b661091257a4175bacf4a81cf6c0cf9)](https://www.codacy.com/gh/siakamansaly/ToDoAndCo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=siakamansaly/ToDoAndCo&amp;utm_campaign=Badge_Grade)

</div>
<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/siakamansaly/Audit-and-Improve-Symfony-App">
    <img src="public/img/Logo_OpenClassrooms.png" alt="Logo">
  </a>
  <h1 align="center">Audit and Improve Symfony App</h1>
  <p align="center">
    To Do & Co
  </p>
</div>

<!-- ABOUT THE PROJECT -->
## About The Project

<div align="center">
    <img src="public/img/Screenshot.png" alt="Screenshot" width="700px">
</div>
<p>Improve, test, document and audit an existing application</p>
<p>The project contains:</p>
<ul>
  <li>Audit code quality and application performance</li>
  <li>Improve the quality of the application and reduce the technical debt of the application</li>
  <li>Implement new features</li>
  <li>Correct some anomalies</li>
  <li>Implement automated tests</li>
  <li>Produce technical documentation for developers</li>
</ul>

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- Built With -->
## Built With

This section list the main frameworks/libraries used to start your project.
<ul>
  <li><a href="https://symfony.com/doc/5.4/index.html" target="_blank">Symfony</a></li>
  <li><a href="https://getbootstrap.com/" target="_blank">Bootstrap</a></li>
  <li><a href="https://jquery.com" target="_blank">JQuery</a></li>
  <li><a href="https://www.php.net/" target="_blank">PHP</a></li>
  <li><a href="https://www.mysql.com/fr/">MySQL</a></li>
  <li><a href="https://twig.symfony.com/" target="_blank">Twig</a></li>
  <li><a href="https://getcomposer.org/" target="_blank">Composer</a></li>
</ul>

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- Prerequisites -->
## Prerequisites

This is the list of things you need to use the software.
   ```sh
      - PHP: >=7.4.0
      - MySQL
      - Composer
   ```
<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running follow these simple example steps :

1.&nbsp;Clone the repo **Audit-and-Improve-Symfony-App**
   ```sh
   git clone https://github.com/siakamansaly/Audit-and-Improve-Symfony-App.git
   ```

2.&nbsp;Install composer packages and npm packages
   ```sh
   cd Audit-and-Improve-Symfony-App
   composer install
   ```
3.&nbsp;You customize variables of file **.env** as needed to run the environment.
   ```sh
   DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
   ```
4.&nbsp;Create database
   ```sh
   php bin/console doctrine:database:create
   ```
   ```sh
   php bin/console doctrine:schema:update --force
   ```
5.&nbsp;Load fixtures
   ```sh
   php bin/console doctrine:fixtures:load
   ```

6.&nbsp;Run project
   ```sh
   php -S localhost:8000 -t public/
   ```  

7.&nbsp;Log in with an account :

   Administrator account
   ```sh
   -Username : admin
   -Password : password
   ```

<!-- OPTIONAL -->
## Tests (optional)

8.&nbsp;Create test database
   ```sh
   php bin/console doctrine:database:create --env=test
   ```
   ```sh
   php bin/console doctrine:schema:update --force --env=test
   ```
9.&nbsp;Run tests 
   ```sh
   php bin/phpunit
   ```


<p align="right">(<a href="#top">back to top</a>)</p>

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1.&nbsp;Fork the Project

2.&nbsp;Create your Feature Branch (`git checkout -b feature/NewFeature`)

3.&nbsp;Create your test and run PHPUnit (`php bin/phpunit`)

4.&nbsp;Commit your Changes (`git commit -m 'Add some NewFeature'`)

5.&nbsp;Push to the Branch (`git push origin feature/NewFeature`)

6.&nbsp;Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- CONTACT -->
## Contact

Siaka MANSALY : [siaka.mansaly@gmail.com](siaka.mansaly@gmail.com) 

LinkedIn : [https://www.linkedin.com/in/siaka-mansaly/](https://www.linkedin.com/in/siaka-mansaly/)

Project Link: [https://github.com/siakamansaly/Audit-and-Improve-Symfony-App](https://github.com/siakamansaly/Audit-and-Improve-Symfony-App)
              
<p align="right">(<a href="#top">back to top</a>)</p>

## Acknowledgments

Thanks to my mentor [Hamza](https://github.com/Hamzasakrani) for his guidance and support!

<ul>
  <li><a href="https://fakerphp.github.io/" target="_blank">Faker</a></li>
  <li><a href="https://github.com/liip/LiipTestFixturesBundle" target="_blank">Liip</a></li>
  <li><a href="https://cs.symfony.com/" target="_blank">PHP CS Fixer</a></li>
  <li><a href="https://www.phpdoc.org/" target="_blank">PHP Documentor</a></li>
  <li><a href="https://phpstan.org/user-guide/getting-started" target="_blank">PHP Stan</a></li>
  <li><a href="https://phpunit.de/index.html" target="_blank">PHP Unit</a></li>
  
  
  
</ul>

<p align="right">(<a href="#top">back to top</a>)</p>
