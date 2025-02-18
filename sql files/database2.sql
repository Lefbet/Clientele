CREATE TABLE Customers
(
	customer_id int(10) NOT NULL AUTO_INCREMENT,
	surname varchar(30) NOT NULL,
	name varchar(30) NOT NULL,
	company_name varchar(100),
	email varchar(50),
	telephone1 varchar(10),
	telephone2 varchar(10),
	comments varchar(500),
	debt decimal(10,2),
	PRIMARY KEY (customer_id)
);

CREATE TABLE Customer_orders
(
	customer_id int(10) NOT NULL,
	order_date date NOT NULL,
	description varchar(250) NOT NULL,
	price decimal(10,2) NOT NULL,
	paid decimal(10,2),
	order_link varchar(250),
	PRIMARY KEY (customer_id, order_date, description),
	FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
);

CREATE TABLE Customer_offers
(
	customer_id int(10) NOT NULL,
	offer_date date NOT NULL,
	description varchar(250) NOT NULL,
	price decimal(10,2) NOT NULL,
	offer_link varchar(250),
	PRIMARY KEY (customer_id, offer_date, description),
	FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
);

CREATE TABLE Suppliers
(
	supplier_id int(10) NOT NULL AUTO_INCREMENT,
	company_name varchar(100) NOT NULL,
	email varchar(50),
	telephone1 varchar(10),
	telephone2 varchar(10),
	bank_accounts varchar(500),
	comments varchar(500),
	self_debt decimal(10,2),
	PRIMARY KEY (supplier_id)
);

CREATE TABLE Purchases
(
	supplier_id int(10) NOT NULL,
	invoice_date date NOT NULL,
	description varchar(50) NOT NULL,
	price decimal(10,2) NOT NULL,
	invoice_link varchar(250),
	PRIMARY KEY (supplier_id, invoice_date, description),
	FOREIGN KEY (supplier_id) REFERENCES Suppliers(supplier_id)
);

ALTER TABLE Customers AUTO_INCREMENT = 2000;
ALTER TABLE Suppliers AUTO_INCREMENT = 2000;
attrib +s +h C:\Users\betso\Documents\Company
