
# データベース: kakeibo

`CREATE DATABASE kakeibo;`
-- --------------------------------------------------------


## テーブルの構造 categories

`CREATE TABLE categories ( id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, name varchar(255) NOT NULL, user_id int(11) NOT NULL, created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8;`

-- --------------------------------------------------------

## テーブルの構造 incomes

`CREATE TABLE incomes (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, user_id int(11) NOT NULL, income_source_id int(11) NOT NULL, amount int(11) NOT NULL, accrual_date date NOT NULL, created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8;`

-- --------------------------------------------------------


## テーブルの構造 income_sources

`CREATE TABLE income_sources (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, name varchar(255) NOT NULL, user_id int(11) NOT NULL, created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8;`

-- --------------------------------------------------------


 ## テーブルの構造 spendings

`CREATE TABLE spendings (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, name varchar(255) NOT NULL, user_id int(11) NOT NULL, category_id int(11) NOT NULL, amount int(11) NOT NULL, accrual_date date NOT NULL, created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8;`


-- --------------------------------------------------------


## テーブルの構造 users

`CREATE TABLE users (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, email varchar(255) NOT NULL, password varchar(255) NOT NULL, created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8;`


