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
  `description` TEXT NOT NULL
);

CREATE TABLE `members` (
  `userId` INTEGER NOT NULL,
  `projectId` INTEGER NOT NULL,
  PRIMARY KEY (`userId`, `projectId`)
);