/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : jam00

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-12-16 17:44:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_assignment`;
CREATE TABLE `t_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `t_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户-角色的关联表';

-- ----------------------------
-- Records of t_auth_assignment
-- ----------------------------
INSERT INTO `t_auth_assignment` VALUES ('普通用户', '2', '1481275395');
INSERT INTO `t_auth_assignment` VALUES ('权限管理', '1', '1481088568');
INSERT INTO `t_auth_assignment` VALUES ('浏览用户', '1', '1480583414');
INSERT INTO `t_auth_assignment` VALUES ('用户管理', '1', '1481248984');
INSERT INTO `t_auth_assignment` VALUES ('超级管理员', '1', '1480904942');

-- ----------------------------
-- Table structure for `t_auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_item`;
CREATE TABLE `t_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `t_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `t_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用于存储角色、权限和路由';

-- ----------------------------
-- Records of t_auth_item
-- ----------------------------
INSERT INTO `t_auth_item` VALUES ('/admin/*', '2', null, null, null, '1480903517', '1480903517');
INSERT INTO `t_auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/assignment/error', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/default/error', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1481088227', '1481088227');
INSERT INTO `t_auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/menu/error', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/error', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/error', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/route/error', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/rule/error', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1481088228', '1481088228');
INSERT INTO `t_auth_item` VALUES ('/debug/*', '2', null, null, null, '1480917614', '1480917614');
INSERT INTO `t_auth_item` VALUES ('/gii/*', '2', null, null, null, '1480917242', '1480917242');
INSERT INTO `t_auth_item` VALUES ('/site/error', '2', null, null, null, '1481180469', '1481180469');
INSERT INTO `t_auth_item` VALUES ('/site/index', '2', null, null, null, '1481180469', '1481180469');
INSERT INTO `t_auth_item` VALUES ('/site/login', '2', null, null, null, '1481180469', '1481180469');
INSERT INTO `t_auth_item` VALUES ('/site/logout', '2', null, null, null, '1481180469', '1481180469');
INSERT INTO `t_auth_item` VALUES ('/user-backend/*', '2', null, null, null, '1481179458', '1481179458');
INSERT INTO `t_auth_item` VALUES ('/user-backend/create', '2', null, null, null, '1481179458', '1481179458');
INSERT INTO `t_auth_item` VALUES ('/user-backend/delete', '2', null, null, null, '1481179458', '1481179458');
INSERT INTO `t_auth_item` VALUES ('/user-backend/index', '2', null, null, null, '1481179458', '1481179458');
INSERT INTO `t_auth_item` VALUES ('/user-backend/signup', '2', null, null, null, '1481179458', '1481179458');
INSERT INTO `t_auth_item` VALUES ('/user-backend/update', '2', null, null, null, '1481179458', '1481179458');
INSERT INTO `t_auth_item` VALUES ('/user-backend/view', '2', null, null, null, '1481179458', '1481179458');
INSERT INTO `t_auth_item` VALUES ('debug使用', '2', null, null, null, '1480917590', '1480917590');
INSERT INTO `t_auth_item` VALUES ('gii操作', '2', null, null, null, '1480917377', '1480917377');
INSERT INTO `t_auth_item` VALUES ('普通权限', '2', '只能登录退出和浏览首页', null, null, '1481180537', '1481180581');
INSERT INTO `t_auth_item` VALUES ('普通用户', '1', null, null, null, '1481180508', '1481180508');
INSERT INTO `t_auth_item` VALUES ('权限管理', '2', null, null, null, '1480904719', '1480904719');
INSERT INTO `t_auth_item` VALUES ('浏览用户', '1', '只能浏览指定数据', null, null, '1480583414', '1481180695');
INSERT INTO `t_auth_item` VALUES ('用户管理', '2', null, null, null, '1481180426', '1481180426');
INSERT INTO `t_auth_item` VALUES ('用户管理员', '1', null, null, null, '1481182705', '1481182705');
INSERT INTO `t_auth_item` VALUES ('超级管理员', '1', null, null, null, '1480904846', '1481182744');

-- ----------------------------
-- Table structure for `t_auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_item_child`;
CREATE TABLE `t_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `t_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色-权限的关联表';

-- ----------------------------
-- Records of t_auth_item_child
-- ----------------------------
INSERT INTO `t_auth_item_child` VALUES ('权限管理', '/admin/*');
INSERT INTO `t_auth_item_child` VALUES ('debug使用', '/debug/*');
INSERT INTO `t_auth_item_child` VALUES ('gii操作', '/gii/*');
INSERT INTO `t_auth_item_child` VALUES ('普通权限', '/site/error');
INSERT INTO `t_auth_item_child` VALUES ('普通权限', '/site/index');
INSERT INTO `t_auth_item_child` VALUES ('普通权限', '/site/login');
INSERT INTO `t_auth_item_child` VALUES ('普通权限', '/site/logout');
INSERT INTO `t_auth_item_child` VALUES ('用户管理', '/user-backend/*');
INSERT INTO `t_auth_item_child` VALUES ('超级管理员', 'debug使用');
INSERT INTO `t_auth_item_child` VALUES ('超级管理员', 'gii操作');
INSERT INTO `t_auth_item_child` VALUES ('普通用户', '普通权限');
INSERT INTO `t_auth_item_child` VALUES ('浏览用户', '普通用户');
INSERT INTO `t_auth_item_child` VALUES ('超级管理员', '普通用户');
INSERT INTO `t_auth_item_child` VALUES ('超级管理员', '权限管理');
INSERT INTO `t_auth_item_child` VALUES ('超级管理员', '浏览用户');
INSERT INTO `t_auth_item_child` VALUES ('用户管理员', '用户管理');

-- ----------------------------
-- Table structure for `t_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_rule`;
CREATE TABLE `t_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of t_auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `t_menu`
-- ----------------------------
DROP TABLE IF EXISTS `t_menu`;
CREATE TABLE `t_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `t_menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `t_menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_menu
-- ----------------------------
INSERT INTO `t_menu` VALUES ('1', '权限配置', null, null, '99', 0x69636F6E2D636F6773);
INSERT INTO `t_menu` VALUES ('5', '分配权限', '1', '/admin/assignment/index', '5', null);
INSERT INTO `t_menu` VALUES ('6', '角色列表', '1', '/admin/role/index', '4', null);
INSERT INTO `t_menu` VALUES ('7', '权限列表', '1', '/admin/permission/index', '3', null);
INSERT INTO `t_menu` VALUES ('8', '路由列表', '1', '/admin/route/index', '2', null);
INSERT INTO `t_menu` VALUES ('9', '规则列表', '1', '/admin/rule/index', '6', null);
INSERT INTO `t_menu` VALUES ('10', '菜单列表', '1', '/admin/menu/index', '1', 0x69636F6E2D6C697374);
INSERT INTO `t_menu` VALUES ('11', '用户管理', null, null, null, 0x69636F6E2D67726F7570);
INSERT INTO `t_menu` VALUES ('12', '新增用户', '11', '/user-backend/signup', '2', null);
INSERT INTO `t_menu` VALUES ('13', '用户列表', '11', '/user-backend/index', '1', 0x69636F6E2D6C697374);

-- ----------------------------
-- Table structure for `t_user`
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` char(15) NOT NULL COMMENT '用户名',
  `password` char(64) DEFAULT NULL COMMENT '密码',
  `email` char(32) DEFAULT NULL COMMENT '邮箱',
  `auth_key` char(32) DEFAULT NULL COMMENT '记住我的认证key',
  `notice` smallint(6) unsigned DEFAULT '0' COMMENT '提醒数',
  `group_id` int(10) unsigned NOT NULL COMMENT '用户组id',
  `time_login` datetime NOT NULL COMMENT '最后登录时间',
  `time_register` datetime NOT NULL COMMENT '注册时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态，0删除，1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('1', 'admin', '$2y$13$fuL2xOXfc.croDwz3SAW/.YdrvQ27otEH41zfdm7iiGDels.L3yOW', 'jam00@vip.qq.com', 'dZ-aW3VXWlmfioF7IZqm_NXmgz0TQBLg', '0', '0', '0000-00-00 00:00:00', '2016-12-01 15:22:58', '1');
INSERT INTO `t_user` VALUES ('2', 'test1', '$2y$13$uFklrEDNChkY4ZyyVLurmubFfRkoOfGOvnQw4qc3WcHapkWz5kYiW', 'test1@qq.com', 'nw__At9MMUx6OUczkZLDZaRPj-HheVHL', '0', '0', '0000-00-00 00:00:00', '2016-12-09 13:39:41', '1');
