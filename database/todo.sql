CREATE TABLE `users` (
  `userId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `username` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `password` VARCHAR(256) NOT NULL
);

--insert users
INSERT INTO `users` VALUES (NULL, "aa", "aa@aa.com", "$2y$10$1FPFN9Cm64LZArbRWLi2A.dx8OS0VxchRnwS9G1VqFID0JbdvV7t.");
INSERT INTO `users` VALUES (NULL, "bb", "bb@bb.com", "$2y$10$jGSUjhhO3s1uvLVF2RNEROEm1lbRCpXx53jiSfsYXOoabjcgClQ2u");

CREATE TABLE `projects` (
  `projectId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `colour` TEXT NOT NULL
);

--insert projects
INSERT INTO `projects` VALUES (NULL, "Best Project", " really good <script>alert('xss');</script>", "yellow");

CREATE TABLE `members` (
  `userId` INTEGER NOT NULL,
  `projectId` INTEGER NOT NULL,
  PRIMARY KEY (`userId`, `projectId`)
);

--insert members
INSERT INTO `members` VALUES (1, 1);
INSERT INTO `members` VALUES (2, 1);

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

--insert todoLists
INSERT INTO `todolists` VALUES (NULL, "A minha lista 1", "", "red", 0, "", 1, 0);
INSERT INTO `todolists` VALUES (NULL, "A minha lista 2", "", "white", 0, "", 2, 0);
INSERT INTO `todolists` VALUES (NULL, "lista do projeto 1", "complicated, hardcore", "teal", 0, "", 1, 1);
INSERT INTO `todolists` VALUES (NULL, "Segunda lista do projeto 1", "hardcore, awesome", "orange", 0, "", 1, 1);

CREATE TABLE `items` (
  `itemId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `completed` INTEGER NOT NULL DEFAULT 0,
  `content` TEXT NOT NULL,
  `dueDate` DATETIME NOT NULL,
  `todoListId` INTEGER NOT NULL
);

--insert items
INSERT INTO `items` VALUES (NULL, 0, "banho ao cao", "", 1);
INSERT INTO `items` VALUES (NULL, 0, "comer as bolachas", "", 2);
INSERT INTO `items` VALUES (NULL, 1, "provar o chocolate do projeto", "", 3);
INSERT INTO `items` VALUES (NULL, 1, "fazer aquela cena no melhor projeto", "", 3);
INSERT INTO `items` VALUES (NULL, 0, "limpar a casa", "2017-11-29", 4);
INSERT INTO `items` VALUES (NULL, 1, "limpar a casa", "2017-11-29", 4);

CREATE TABLE `assignments` (
  `userId` INTEGER NOT NULL,
  `todoListId` INTEGER NOT NULL,
  PRIMARY KEY (`userId`, `todoListId`)
);

--insert assignments
INSERT INTO `assignments` VALUES (1, 4);
