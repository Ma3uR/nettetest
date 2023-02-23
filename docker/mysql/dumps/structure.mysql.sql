CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active | 0=Inactive',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active | 0=Inactive',
  PRIMARY KEY (`id`),
  FOREIGN KEY (country_id) REFERENCES countries(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `age`  int NOT NULL,
  `gender` tinyint(1) NOT NULL COMMENT '1=male | 2=female',
  `gender_search` tinyint(1) NOT NULL COMMENT '1=male | 2=female',
  `profile_text` varchar(128) NOT NULL,
  `country_id` int NOT NULL,
  `city_id` int NOT NULL,

  PRIMARY KEY (`id`),
  FOREIGN KEY (country_id) REFERENCES countries(id),
  FOREIGN KEY (city_id) REFERENCES cities(id),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;