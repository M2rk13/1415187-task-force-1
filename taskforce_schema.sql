CREATE SCHEMA taskforce;

CREATE TABLE taskforce.categories ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	name                 varchar(64)  NOT NULL    ,
	icon                 varchar(128)      
 ) engine=InnoDB;

ALTER TABLE taskforce.categories COMMENT '������ ���������';

CREATE TABLE taskforce.cities ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	name                 varchar(64)  NOT NULL    ,
	coordinates          json  NOT NULL    
 ) engine=InnoDB;

ALTER TABLE taskforce.cities COMMENT '������ �������';

ALTER TABLE taskforce.cities MODIFY coordinates json  NOT NULL   COMMENT '��� ����������, �������������� ������������ ������ ��� ���� ������ ����������.';

CREATE TABLE taskforce.notification_types ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	name                 varchar(128)  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE taskforce.statuses ( 
	id                   int  NOT NULL    PRIMARY KEY,
	name                 varchar(64)  NOT NULL    
 ) engine=InnoDB;

ALTER TABLE taskforce.statuses COMMENT '������� �������';

CREATE TABLE taskforce.users ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	email                varchar(128)  NOT NULL    ,
	name                 varchar(128)  NOT NULL    ,
	password             char(64)  NOT NULL    ,
	date_add             datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP   ,
	date_activity        datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP   ,
	is_visible           float  NOT NULL DEFAULT true   ,
	city_id              int      ,
	adress               varchar(255)      ,
	birthday             date      ,
	phone                varchar(11)      ,
	skype                varchar(64)      ,
	telegram             varchar(64)      ,
	avatar               varchar(128)      ,
	about                text      ,
	is_deleted           boolean  NOT NULL DEFAULT false   ,
	CONSTRAINT fk_users_cities FOREIGN KEY ( city_id ) REFERENCES taskforce.cities( id ) ON DELETE SET NULL ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.users MODIFY date_activity datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP  COMMENT '����� ��������� ���������� �� �����';

ALTER TABLE taskforce.users MODIFY is_visible float  NOT NULL DEFAULT true  COMMENT '����������/�������� ������� ������������.\n���� ������������ �������� - ������ �������� �� �������� ������������.\n���� ������������ ����������� - ������ ����� �������� �� �������� ������������.';

ALTER TABLE taskforce.users MODIFY city_id int     COMMENT '������������� ������ �� ������� �������';

ALTER TABLE taskforce.users MODIFY adress varchar(255)     COMMENT '����� ������������';

CREATE TABLE taskforce.favorites ( 
	customer_id          int  NOT NULL    ,
	executor_id          int  NOT NULL    ,
	CONSTRAINT fk_favorites_customer FOREIGN KEY ( customer_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_favorites_executor FOREIGN KEY ( executor_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.favorites COMMENT '��������� �����������';

CREATE TABLE taskforce.images_of_work ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	user_id              int  NOT NULL    ,
	filename             varchar(255)  NOT NULL    ,
	CONSTRAINT fk_images_of_work_users FOREIGN KEY ( user_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.images_of_work COMMENT '���� ����� ������������';

CREATE TABLE taskforce.specialisations ( 
	executor_id          int  NOT NULL    ,
	category_id          int  NOT NULL    ,
	CONSTRAINT fk_specialisations_users FOREIGN KEY ( executor_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_specialisations_categories FOREIGN KEY ( category_id ) REFERENCES taskforce.categories( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.specialisations COMMENT '������������� ������������';

CREATE TABLE taskforce.tasks ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	name                 varchar(128)  NOT NULL    ,
	description          text  NOT NULL    ,
	category_id          int  NOT NULL    ,
	status_id            int  NOT NULL    ,
	price                int  NOT NULL    ,
	customer_id          int  NOT NULL    ,
	date_add             datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP   ,
	executor_id          int      ,
	adress               varchar(255)      ,
	city_id              int      ,
	expire               date      ,
	CONSTRAINT fk_tasks_categories FOREIGN KEY ( category_id ) REFERENCES taskforce.categories( id ) ON DELETE RESTRICT ON UPDATE RESTRICT,
	CONSTRAINT fk_tasks_statuses FOREIGN KEY ( status_id ) REFERENCES taskforce.statuses( id ) ON DELETE NO ACTION ON UPDATE NO ACTION,
	CONSTRAINT fk_tasks_customer FOREIGN KEY ( customer_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_tasks_executor FOREIGN KEY ( executor_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_tasks_cities FOREIGN KEY ( city_id ) REFERENCES taskforce.cities( id ) ON DELETE SET NULL ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.tasks MODIFY name varchar(128)  NOT NULL   COMMENT '��������� �������';

ALTER TABLE taskforce.tasks MODIFY description text  NOT NULL   COMMENT '����� �������';

ALTER TABLE taskforce.tasks MODIFY category_id int  NOT NULL   COMMENT '������������� ��������� �� ������� ����� ���������';

ALTER TABLE taskforce.tasks MODIFY status_id int  NOT NULL   COMMENT '������������� ������� �� ������� �������� �������';

ALTER TABLE taskforce.tasks MODIFY price int  NOT NULL   COMMENT '���� ���������';

ALTER TABLE taskforce.tasks MODIFY customer_id int  NOT NULL   COMMENT '������������� ��������� �� ������� �������������';

ALTER TABLE taskforce.tasks MODIFY executor_id int     COMMENT '������������� ����������� �� ������� �������������';

ALTER TABLE taskforce.tasks MODIFY city_id int     COMMENT '������������� ������ �� ������� �������';

ALTER TABLE taskforce.tasks MODIFY expire date     COMMENT '���� ���������� �������';

CREATE TABLE taskforce.user_notification ( 
	user_id              int  NOT NULL    ,
	notification_id      int  NOT NULL    ,
	CONSTRAINT fk_user_notification_users FOREIGN KEY ( user_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_user_notification FOREIGN KEY ( notification_id ) REFERENCES taskforce.notification_types( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.user_notification COMMENT '������� �������� ����������� ������������.\n���� ������ ���������� - ����������� �������.';

ALTER TABLE taskforce.user_notification MODIFY notification_id int  NOT NULL   COMMENT '������������� ���� �����������';

CREATE TABLE taskforce.feedbacks ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	task_id              int  NOT NULL    ,
	executor_id          int  NOT NULL    ,
	rate                 int  NOT NULL    ,
	created_at           datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP   ,
	description          text      ,
	CONSTRAINT fk_feedbacks_tasks FOREIGN KEY ( task_id ) REFERENCES taskforce.tasks( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_feedbacks_users FOREIGN KEY ( executor_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.feedbacks COMMENT '������ ������������� �� ������������';

ALTER TABLE taskforce.feedbacks MODIFY task_id int  NOT NULL   COMMENT '������������� �������';

CREATE TABLE taskforce.messages ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	sender_id            int  NOT NULL    ,
	receiver_id          int  NOT NULL    ,
	task_id              int  NOT NULL    ,
	message              text  NOT NULL    ,
	created_at           datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP   ,
	CONSTRAINT fk_messages_sender FOREIGN KEY ( sender_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_messages_receiver FOREIGN KEY ( receiver_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_messages_tasks FOREIGN KEY ( task_id ) REFERENCES taskforce.tasks( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

CREATE TABLE taskforce.responces ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	task_id              int  NOT NULL    ,
	executor_id          int  NOT NULL    ,
	created_at           datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP   ,
	price                int      ,
	comment              text      ,
	CONSTRAINT fk_responces_tasks FOREIGN KEY ( task_id ) REFERENCES taskforce.tasks( id ) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT fk_responces_users FOREIGN KEY ( executor_id ) REFERENCES taskforce.users( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.responces COMMENT '������� �������� ������������';

ALTER TABLE taskforce.responces MODIFY task_id int  NOT NULL   COMMENT '������������� �������';

ALTER TABLE taskforce.responces MODIFY executor_id int  NOT NULL   COMMENT '������������� ����������� �� ������� �������������';

ALTER TABLE taskforce.responces MODIFY price int     COMMENT '���� �����������';

CREATE TABLE taskforce.task_files ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	task_id              int  NOT NULL    ,
	filename             varchar(128)  NOT NULL    ,
	CONSTRAINT fk_task_files_tasks FOREIGN KEY ( task_id ) REFERENCES taskforce.tasks( id ) ON DELETE CASCADE ON UPDATE NO ACTION
 ) engine=InnoDB;

ALTER TABLE taskforce.task_files COMMENT '�����, ������������� � ��������';
