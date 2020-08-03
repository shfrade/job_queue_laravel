-- drop database jobs_queue;

create database jobs_queue;

use jobs_queue;

create table submitter (
	submitter_id int not null primary key auto_increment,
	username varchar(200) not null unique,
	created_at datetime not null default current_timestamp
)ENGINE = InnoDB;

create table processor (
	processor_id int not null primary key auto_increment,
	hostname varchar(64) not null unique,
	created_at datetime not null default current_timestamp
)ENGINE = InnoDB;

create table job (
	job_id int not null primary key auto_increment,
	submitter_id int not null,
	command TEXT NOT NULL,
	created_at datetime not null default current_timestamp,
	priority ENUM('0', '1', '2', '3', '4', '5') NOT NULL DEFAULT '5' COMMENT 'Highest (0) ... (5) Lowest',
	constraint fk_job__submitter_id
    foreign key (submitter_id)
    references submitter (submitter_id)
)ENGINE = InnoDB;

create table execution (
	job_id int not null unique,
	processor_id int not null,
	created_at datetime not null default current_timestamp,
	finished_at datetime null,
	primary key (job_id,processor_id),
	constraint fk_processor__job_id
    foreign key (job_id)
    references job (job_id),
    constraint fk_processor__processor_id
    foreign key (processor_id)
    references processor (processor_id)
)ENGINE = InnoDB;
