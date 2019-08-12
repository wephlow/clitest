# CLI test

This test is designed to test the follwing:
* your understanding of written requirements
* your understanding of PHP
* your ability to work on a custom-build code
* your knowledge of cli debugging techniques
* your skill at expanding existing code

Please note, this code is not designed to to anything important. It is designed to follow a logic. The goal of this test is not to provide alternative ways of implementing the same results (_that can be done with a single SQL_), but to test your ability to take an existing code, fix it and test it.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

This test is a CLI application written in PHP and must be run through php command line interface. To run the test properly, the machine must have access to the following additional applications:
* RabbitMQ
* MySQL

### Installing

To install the required code, from the terminal launch

```
composer install
```

Create the file _.env_ and duplicate the content of _.env.example_

```
cp .env.example .env
```

### Setup

To set up the project properly, you need to create a databse with two tables in MySQL, and configure the _.env_ file to ensure you have the proper connection to MySQL and RabbitMQ. 

#### MySQL

The MySQL server must be configured so to have a database with two tables, `actions` and `names`.

The table `actions` contains a list of unique actions which should be grouped together the resulting values added to the table `names`.

You can use the following SQL to create the two tables:

```sql

CREATE TABLE `actions` (
  `actionId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `initialValue` float(10,3) NOT NULL,
  `isAnalysed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `actions`
  ADD PRIMARY KEY (`actionId`);
  
ALTER TABLE `actions`
  MODIFY `actionId` int(11) NOT NULL AUTO_INCREMENT;

```

```sql

CREATE TABLE `names` (
  `nameId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `totalValue` float(10,3) NOT NULL,
  `averageValue` float(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `names`
  ADD PRIMARY KEY (`nameId`),
  ADD UNIQUE KEY `name` (`name`);
  
ALTER TABLE `names`
MODIFY `nameId` int(11) NOT NULL AUTO_INCREMENT;

```

Please make sure to add the following dummy data:

```sql

INSERT INTO `actions`(`name`, `initialValue`, `isAnalysed`) VALUES ('London',10,0), ('Rome',10,0),('London',5,0),('London',15,0),('New York',8,0),('Rome',10,0);

```

The connection to MySQL in the _.env_ file is defined in this two lines:

```
clitestdb="HOST,USER,PASSWORD,DATABASENAME,PORT"
```

#### RabbitMQ

The only configuration to ensure RabbitMQ works properly is on this line in the _.env_ file, which you should configure properly

```
RABBITMQ_CONNECTION="HOST,PORT,USER,PASSWORD"
``` 

## Expected Functionality

The `dispatcher` reads the `actions` from the database, dispatches them to the queue manager and marks them as analysed, so that they are not taken into consideration any longer.

The `worker` received the actions and either creates a new `names` or updates it if already existing. It should make sure that in the `name` the two fields (`totalValue` and `averageValue`) contains the correct data (sum of all the value in the previous actions sent, and average value of the values sent in all the actions).
 
## The test

The test is designed to be completed in 30' from the moment the local machine is properly set up. You can complete as much of the test as possible while on screen sharing. The microphone must be switched on and the only person allowed to interact with the test is the person which applies for the role.

### 1 - Run the code

The test is composed by two separate endpoints: `dispatcher` and `worker`. The `dispatcher` reads from the table `actions` and adds calls to the queue in RabbitMQ. The `worker` reads from the queue in RabbitMQ and execute the request. You should run the code in the order:

1. `php dispatcher.php`
2. `php worker.php`

Please note that you must run this in **debug mode**, creating a breakpoint at the first possible line of code and understanding the levels of nesting of the code.

### 2 - Find the three bugs in the code

There are three bugs in the code. I want you to understand which ones they are and correct them. After having corrected it I want you to truncate the two tables in the database, re-add the original data in `actions` and run the code again.

### 3 - How would you run a server

Please propose a solution working on a *nix server to make sure that the system works, by launching a dispatcher every x minutes and having a minimum of 10 workers always running as a service.

### 4 - Add a new series of actions and run again

Add 10 additional `actions` and run the code to make sure that these 10 additional values are kept into consideration in the final calculation. Please make sure that you use both new names as well as existing ones.

### 5 - Bonus

Describe a good method to test the system in the long run with thousands of actions, how to automate the process and evaluate the results.