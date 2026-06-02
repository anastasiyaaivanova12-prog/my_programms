create database if not exists `my_calendar`;
use my_calendar;

create table if not exists `tasks` (
	`id` int not null auto_increment primary key,
	`topic` varchar(255) not null,
	`type` varchar(255),
	`place` varchar(255),
	`datetime` datetime not null,
	`duration`int default 1, /*измеряется в часах*/
	`comment` text,
	`created_at` timestamp default current_timestamp,
	`updated_at` timestamp default current_timestamp,
	`status` enum('текущая', 'выполнена', 'просрочена') DEFAULT 'текущая'
);