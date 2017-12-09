PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE `users` (
  `userId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `username` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `password` VARCHAR(256) NOT NULL
);
INSERT INTO "users" VALUES(1,'jcl','jlopes@fe.up.pt','$2y$10$e.cV4dOdlHNKfCzs68m0B.XULEeUk5yuR8CCdzL2ZqAAuz1cY0hpq');
INSERT INTO "users" VALUES(2,'putin','bb@bb.com','$2y$10$Do.RgVkvfuGnWzszpCL4Gulsl8dQvNukGZDWoiCtuByDRijkvLugG');
INSERT INTO "users" VALUES(3,'trump','cc@cc.com','$2y$10$rBhdJkkS13eL7XoQVGL4W.TBRM/QGD0b5FrQe9A1jZLQWu2adhb3m');
INSERT INTO "users" VALUES(4,'monhe','dd@dd.com','$2y$10$xfCI/0uI/xYY1tk9/2DWX.FzcUayIZeucuQ.kIqVyBrReVEGfVGYi');
INSERT INTO "users" VALUES(5,'marcelo','ee@ee.com','$2y$10$NXbx/ZftuJf/KwntPQfEU.F7doPfX5iKxf7/dbcnL2ATtg.R37tsa');
INSERT INTO "users" VALUES(6,'arestivo','arestivo@fe.up.pt','$2y$10$ZB8q7BZ7WwzAXLYV6qbk4ue3yvbdadSJlS/WFbouLZZDEborWUmPS');
INSERT INTO "users" VALUES(7,'obama','obama@white.house.com','$2y$10$saMOolU1lbqFsqlJZ.jTxOHkoEpRtZTC7iuKMU2N.pvBPaSdzVz1e');
INSERT INTO "users" VALUES(8,'dannyps','dannyps@fe.up.pt','$2y$10$ivsgtDgKE0IzGMH0m326YeETS2XYH0ZnTwDildHjJ6ax1QwmfFwMC');
INSERT INTO "users" VALUES(9,'aramos','aramos@fe.up.pt','$2y$10$jbZ8RFkjbcy784dqBrEwiOG7FNl6Lg/Uv9NNYr79wf4NpSISpDZf.');
INSERT INTO "users" VALUES(10,'msramalho','msramalho@fe.up.pt','$2y$10$TpcoZ9/xAMruOcsrefDJ4OPT9/wHW7TnrNlER6N0Nk5R8qxVRxq16');
CREATE TABLE `projects` (
  `projectId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `colour` TEXT NOT NULL
);
INSERT INTO "projects" VALUES(1,'LTW','Laboratório de Tecnologias Web','orange');
INSERT INTO "projects" VALUES(2,'USA','Make America Great Again','red');
INSERT INTO "projects" VALUES(3,'Potenciais Mundiais','Grupo de países que mandam no mundo','red');
INSERT INTO "projects" VALUES(4,'<script>alert("if you see this, we are vulnerable in projects");</script>','Somos os maiores, mas: <script>alert("if you see this, we are vulnerable in project descriptions");</script>','green');
CREATE TABLE `members` (
  `userId` INTEGER NOT NULL,
  `projectId` INTEGER NOT NULL,
  PRIMARY KEY (`userId`, `projectId`)
);
INSERT INTO "members" VALUES(1,1);
INSERT INTO "members" VALUES(3,2);
INSERT INTO "members" VALUES(1,3);
INSERT INTO "members" VALUES(2,3);
INSERT INTO "members" VALUES(3,3);
INSERT INTO "members" VALUES(4,3);
INSERT INTO "members" VALUES(5,3);
INSERT INTO "members" VALUES(7,3);
INSERT INTO "members" VALUES(6,3);
INSERT INTO "members" VALUES(8,1);
INSERT INTO "members" VALUES(9,1);
INSERT INTO "members" VALUES(10,1);
INSERT INTO "members" VALUES(7,2);
INSERT INTO "members" VALUES(1,4);
INSERT INTO "members" VALUES(2,4);
INSERT INTO "members" VALUES(3,4);
INSERT INTO "members" VALUES(4,4);
INSERT INTO "members" VALUES(5,4);
INSERT INTO "members" VALUES(6,4);
INSERT INTO "members" VALUES(7,4);
INSERT INTO "members" VALUES(8,4);
INSERT INTO "members" VALUES(9,4);
INSERT INTO "members" VALUES(10,4);
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
INSERT INTO "todolists" VALUES(1,'Apresentação','interessante,feup','yellow',0,'',1,0);
INSERT INTO "todolists" VALUES(2,'Funcionalidades extra','hardcore,ltw','orange',0,'',1,0);
INSERT INTO "todolists" VALUES(3,'Funcionalidades de Segurança','feup,ltw','teal',0,'',1,1);
INSERT INTO "todolists" VALUES(4,'Atalhos de Teclado','hardcore,geek','orange',0,'',1,0);
INSERT INTO "todolists" VALUES(5,'Páginas Existentes','interface,ltw','blue',0,'',1,0);
INSERT INTO "todolists" VALUES(6,'Lista pessoal do Putin','hardcore','pink',0,'',2,0);
INSERT INTO "todolists" VALUES(7,'Lista de prioridades do Trump','usa,greatAgain','purple',0,'',3,2);
INSERT INTO "todolists" VALUES(8,'Socialistas ao poder','','brown',0,'',4,0);
INSERT INTO "todolists" VALUES(9,'Lista dos Líderes','poder,hardcore,feup','red',0,'',1,3);
INSERT INTO "todolists" VALUES(10,'Lista ARQUIVADA dos Líderes','geek,poder','white',1,'',1,0);
INSERT INTO "todolists" VALUES(11,'Notas dos Alunos','feup,avaliação','green',0,'',7,0);
INSERT INTO "todolists" VALUES(12,'Coisas que tenho para fazer','','white',0,'',7,0);
INSERT INTO "todolists" VALUES(13,'Mais umas notas soltas','','white',0,'',7,0);
INSERT INTO "todolists" VALUES(14,'Exemplos de pesquisas','hardcore,ltw,feup','pink',0,'',1,0);
INSERT INTO "todolists" VALUES(15,'XSS: <script>alert("if you see this, we are vulnerable in lists");</script>','hardcore,ltw,feup','pink',0,'',1,0);
CREATE TABLE `items` (
  `itemId` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  `completed` INTEGER NOT NULL DEFAULT 0,
  `content` TEXT NOT NULL,
  `dueDate` DATETIME NOT NULL,
  `todoListId` INTEGER NOT NULL
);
INSERT INTO "items" VALUES(1,0,'Todas as funcionalidades base foram implementadas','2017-12-08 23:12:05',1);
INSERT INTO "items" VALUES(2,0,'Mostrar Lista de funcionalidades extra','2017-12-08 23:12:21',1);
INSERT INTO "items" VALUES(3,1,'Criar Projeto','2017-12-08 23:14:57',2);
INSERT INTO "items" VALUES(4,1,'Gerir Projetos','2017-12-08 23:15:17',2);
INSERT INTO "items" VALUES(5,1,'Gerir Membros de Projetos','2017-12-08 23:17:34',2);
INSERT INTO "items" VALUES(6,1,'Partilhar listas (readonly)','2017-12-08 23:17:41',2);
INSERT INTO "items" VALUES(7,1,'Partilhar listas em projetos','2017-12-08 23:17:56',2);
INSERT INTO "items" VALUES(8,1,'Editar Tags','2017-12-08 23:18:01',2);
INSERT INTO "items" VALUES(9,1,'Pesquisa super flexível','2017-12-08 23:18:13',2);
INSERT INTO "items" VALUES(10,1,'Pesquisa que perdura (bookmark)','2017-12-08 23:18:24',2);
INSERT INTO "items" VALUES(11,0,'Mostrar Lista de funcionalidades de segurança','2017-12-08 23:19:07',1);
INSERT INTO "items" VALUES(12,1,'Passwords guardadas em hashes','2017-12-08 23:19:23',3);
INSERT INTO "items" VALUES(13,1,'Páginas que requerem autenticação','2017-12-08 23:19:53',3);
INSERT INTO "items" VALUES(14,1,'Páginas que requerem autenticação e permissão','2017-12-08 23:20:16',3);
INSERT INTO "items" VALUES(15,1,'CSRF tokens em forms','2017-12-08 23:20:28',3);
INSERT INTO "items" VALUES(16,1,'CSRF tokens em ajax','2017-12-08 23:20:34',3);
INSERT INTO "items" VALUES(17,1,'XSS prevention (<script>alert("if you see this, we are vulnerable in items");</script>)','2017-12-08 23:21:06',3);
INSERT INTO "items" VALUES(18,1,'Adicionar e editar foto de perfil','2017-12-08 23:21:30',2);
INSERT INTO "items" VALUES(19,1,'Criação de thumbnails e fotos em tamanho grande','2017-12-08 23:22:01',2);
INSERT INTO "items" VALUES(20,0,'Cores em Listas e em Projetos','2017-12-08 23:22:39',2);
INSERT INTO "items" VALUES(21,0,'Mobile Friendly','2017-12-08 23:22:50',1);
INSERT INTO "items" VALUES(22,0,'Mostrar atalhos de teclado','2017-12-08 23:23:06',1);
INSERT INTO "items" VALUES(23,0,'Ctrl+F abre a pesquisa','2017-12-08 23:23:31',4);
INSERT INTO "items" VALUES(25,0,'Ctrl+S abre a Sidebar','2017-12-08 23:23:50',4);
INSERT INTO "items" VALUES(26,0,'Ctrl+P abre o modal de um projeto','2017-12-08 23:24:00',4);
INSERT INTO "items" VALUES(27,0,'Ctrl+L abre o modal de nova lista','2017-12-08 23:24:11',4);
INSERT INTO "items" VALUES(28,0,'ESC limpa a pesquisa ou fecha os Modals','2017-12-08 23:24:26',4);
INSERT INTO "items" VALUES(29,0,'Mostrar Páginas Existentes','2017-12-08 23:25:59',1);
INSERT INTO "items" VALUES(30,1,'Index','2017-12-08 23:32:35',5);
INSERT INTO "items" VALUES(31,1,'Register','2017-12-08 23:32:38',5);
INSERT INTO "items" VALUES(32,1,'Login','2017-12-08 23:32:58',5);
INSERT INTO "items" VALUES(33,1,'Dashboard','2017-12-08 23:33:01',5);
INSERT INTO "items" VALUES(34,1,'Edit Profile','2017-12-08 23:33:08',5);
INSERT INTO "items" VALUES(35,1,'User','2017-12-08 23:33:09',5);
INSERT INTO "items" VALUES(36,1,'Project','2017-12-08 23:33:25',5);
INSERT INTO "items" VALUES(37,1,'List','2017-12-08 23:33:31',5);
INSERT INTO "items" VALUES(38,1,'Editar password','2017-12-08 23:36:08',2);
INSERT INTO "items" VALUES(39,1,'Estatísticas do Projeto','2017-12-08 23:36:31',2);
INSERT INTO "items" VALUES(40,1,'Estatísticas da Plataforma','2017-12-08 23:36:42',2);
INSERT INTO "items" VALUES(41,1,'Anexar Crimeia','2017-12-08 23:38:04',6);
INSERT INTO "items" VALUES(42,1,'Comprar eleições americanas','2017-12-08 23:38:18',6);
INSERT INTO "items" VALUES(43,1,'Dominar Médio Oriente','2017-12-08 23:38:26',6);
INSERT INTO "items" VALUES(44,0,'Dominar Àsia','2017-12-08 23:38:36',6);
INSERT INTO "items" VALUES(45,0,'Muro do méxico','2017-12-08 23:42:23',7);
INSERT INTO "items" VALUES(46,1,'Grab them by the P****','2017-12-08 23:42:35',7);
INSERT INTO "items" VALUES(47,1,'Hillary Clinton na rua','2017-12-08 23:42:42',7);
INSERT INTO "items" VALUES(48,0,'Terra sem muçulmanos','2017-12-08 23:43:00',7);
INSERT INTO "items" VALUES(49,0,'Crescer cabelo','2017-12-08 23:43:08',7);
INSERT INTO "items" VALUES(51,1,'Calar o PSD','2017-12-08 23:45:21',8);
INSERT INTO "items" VALUES(52,1,'Calar o Passos Coelho','2017-12-08 23:45:27',8);
INSERT INTO "items" VALUES(53,1,'Calar o BE','2017-12-08 23:45:29',8);
INSERT INTO "items" VALUES(54,1,'Calar o PCP','2017-12-08 23:45:33',8);
INSERT INTO "items" VALUES(55,1,'Rir do PAN','2017-12-08 23:45:36',8);
INSERT INTO "items" VALUES(56,1,'Jantar no palácio da bolsa','2017-12-08 23:45:43',8);
INSERT INTO "items" VALUES(57,0,'Ser presidente de Portugal e Índia ao mesmo tempo','2017-12-08 23:46:07',8);
INSERT INTO "items" VALUES(58,0,'Obama','2017-12-08 23:50:54',9);
INSERT INTO "items" VALUES(59,0,'Putin','2017-12-08 23:50:57',9);
INSERT INTO "items" VALUES(60,0,'Trump','2017-12-08 23:51:07',9);
INSERT INTO "items" VALUES(61,0,'António Costa','2017-12-08 23:51:13',9);
INSERT INTO "items" VALUES(62,0,'Marcelo','2017-12-08 23:51:15',9);
INSERT INTO "items" VALUES(63,0,'João Correia Lopes','2017-12-08 23:51:17',9);
INSERT INTO "items" VALUES(64,0,'André Restivo','2017-12-08 23:51:47',9);
INSERT INTO "items" VALUES(65,1,'Item feito1','2017-12-09 00:04:09',10);
INSERT INTO "items" VALUES(66,1,'Item feito2','2017-12-09 00:04:13',10);
INSERT INTO "items" VALUES(67,1,'Afonso - 20','2017-12-09 00:05:11',11);
INSERT INTO "items" VALUES(68,1,'Daniel - 20','2017-12-09 00:05:14',11);
INSERT INTO "items" VALUES(69,1,'Miguel - 20','2017-12-09 00:05:22',11);
DELETE FROM sqlite_sequence;
INSERT INTO "sqlite_sequence" VALUES('users',10);
INSERT INTO "sqlite_sequence" VALUES('todolists',15);
INSERT INTO "sqlite_sequence" VALUES('projects',4);
INSERT INTO "sqlite_sequence" VALUES('items',74);
COMMIT;
