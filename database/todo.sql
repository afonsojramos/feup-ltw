CREATE TABLE `users` (
  `userId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `username` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `password` VARCHAR(256) NOT NULL
);

/* INSERT INTO `users` VALUES (NULL, "aa", "aa@aa.com", "lolIsHashed");
INSERT INTO `users` VALUES (NULL, "bb", "bb@bb.com", "lolIsHashed");
INSERT INTO `users` VALUES (NULL, "cc", "cc@cc.com", "lolIsHashed");
INSERT INTO `users` VALUES (NULL, "dd", "dd@dd.com", "lolIsHashed"); */

CREATE TABLE `projects` (
  `projectId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `colour` TEXT NOT NULL
);

CREATE TABLE `members` (
  `userId` INTEGER NOT NULL,
  `projectId` INTEGER NOT NULL,
  PRIMARY KEY (`userId`, `projectId`)
);

CREATE TABLE `todolists` (
  `todoListId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `title` TEXT NOT NULL,
  `tags` TEXT NOT NULL,
  `colour` TEXT NOT NULL,
  `archived` INTEGER NOT NULL DEFAULT 0,
  `link` TEXT,
  `userId` INTEGER NOT NULL,
  `projectId` INTEGER DEFAULT 0
);

CREATE TABLE `items` (
  `itemId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `completed` INTEGER NOT NULL DEFAULT 0,
  `content` TEXT NOT NULL,
  `dueDate` DATETIME NOT NULL,
  `todoListId` INTEGER NOT NULL
);

CREATE TABLE `assignments` (
  `userId` INTEGER NOT NULL,
  `todoListId` INTEGER NOT NULL,
  PRIMARY KEY (`userId`, `todoListId`)
);