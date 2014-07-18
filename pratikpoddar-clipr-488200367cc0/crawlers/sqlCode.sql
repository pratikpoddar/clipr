LOAD XML LOCAL INFILE 'items2.xml' INTO TABLE productDetail rows identified by '<item>';

drop table `test`.`productDetail`;
CREATE  TABLE IF NOT EXISTS `test`.`productDetail` (
  `productid` INT NOT NULL AUTO_INCREMENT ,
  `link` VARCHAR(500) NOT NULL ,
  `title` VARCHAR(200) NOT NULL ,
  `markprice` INT UNSIGNED NOT NULL ,
  `price` INT UNSIGNED NOT NULL ,
  `description` BLOB NULL ,
  `description2` BLOB NULL ,
  `recid` Blob NULL ,
  `image` VarChar(1000) NOT NULL,
  `buylink` VARCHAR(200) NULL ,
  `category` VARCHAR(400) NULL ,
  `demographics` SMALLINT(6) NULL ,
  `timestamp` Timestamp,
  PRIMARY KEY (`productid`)  )
