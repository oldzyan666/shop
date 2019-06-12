-- phpMyAdmin SQL Dump
-- version 4.0.10deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018-12-20 16:27:31
-- 服务器版本: 5.5.62-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `shop`
--

-- --------------------------------------------------------

--
-- 表的结构 `access_log`
--

CREATE TABLE IF NOT EXISTS `access_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `msg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '操作信息',
  `users` int(10) NOT NULL COMMENT '给哪个用户的',
  `type` tinyint(10) NOT NULL COMMENT '类型：１.推荐有奖',
  `addtime` int(50) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- 转存表中的数据 `access_log`
--

INSERT INTO `access_log` (`id`, `msg`, `users`, `type`, `addtime`) VALUES
(8, '成功推荐gg;获得一级推荐奖励10', 11, 1, 1544513869),
(9, '成功推荐gg;获得二级推荐奖励5', 11, 1, 1544513869),
(10, '成功推荐gg;获得三级推荐奖励3', 11, 1, 1544513869),
(11, '成功推荐rtlhg;获得一级推荐奖励10', 6, 1, 1544665785),
(12, '成功推荐rtlhg;获得一级推荐奖励10', 6, 1, 1544667176),
(13, '成功推荐rtlhg;获得一级推荐奖励10', 6, 1, 1544681651),
(14, '成功推荐rtlhg;获得一级推荐奖励10', 6, 1, 1544687187),
(15, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1544779064),
(16, '成功推荐admin;获得一级推荐奖励10', 0, 1, 1544779102),
(17, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545010145),
(18, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545015059),
(19, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545016981),
(20, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545030107),
(21, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545033385),
(22, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545036802),
(23, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545096809),
(24, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545099631),
(25, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545103461),
(26, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545114957),
(27, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545187177),
(28, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545200899),
(29, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545207074),
(30, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545207310),
(31, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545269344),
(32, '成功推荐tuulice;获得一级推荐奖励10', 0, 1, 1545289787);

-- --------------------------------------------------------

--
-- 表的结构 `addresslist`
--

CREATE TABLE IF NOT EXISTS `addresslist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(15) NOT NULL,
  `rec` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pho` varchar(12) NOT NULL,
  `remark` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `addresslist`
--

INSERT INTO `addresslist` (`id`, `user_id`, `rec`, `city`, `address`, `pho`, `remark`) VALUES
(10, '5', '1111', '北京/海淀区/三环以内', '广东东莞', '15625755639', ''),
(11, '5', '111', '浙江/温州市/泰顺县/罗阳镇', '广东东莞市', '13717383399', ''),
(12, '14', '阮生', '广东/广州市/天河区', '广东省广州市天河区某村中分西某项一号', '156156257525', '');

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pwd` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `user`, `pwd`) VALUES
(1, 'admin', '123456'),
(2, 'aa', '123');

-- --------------------------------------------------------

--
-- 表的结构 `admin_role`
--

CREATE TABLE IF NOT EXISTS `admin_role` (
  `admin_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `admin_role`
--

INSERT INTO `admin_role` (`admin_id`, `role_id`) VALUES
(1, 1),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- 表的结构 `commodity`
--

CREATE TABLE IF NOT EXISTS `commodity` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `tradename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `commodity`
--

INSERT INTO `commodity` (`id`, `tradename`, `img`, `notes`, `time`) VALUES
(2, '小米手机', '20181206/0ae5b0abb7213f9aeec72df0e78c3206.jpg', '5', '2018-12-04'),
(3, 'oppo1', '20181206/cf08c95de9c270486c3a4a4c1c63c9c2.jpg', 'R15', '2018-12-05'),
(4, 'vivo2', '20181206/eb1f45a79e6d8f5c2a70cf5ea0edf2eb.jpg', 'x20', '2018-12-05'),
(5, '红米', '20181205/1423bd650cef23cb3fd8e301725674ac.jpg', '5', '2018-12-04'),
(6, '荣耀手机', '20181205/abe68db6a8d4afce9ba2194e59cb8c2d.jpg', '8', '2018-12-04'),
(7, '苹果电脑', '20181205/c69fb3cca4269123ec597430ac17399a.jpg', '64', '2018-12-04'),
(8, '华为', '20181205/e4c72b7e41fa059131ca2bec0cc3106c.jpg', '8', '2018-12-04'),
(9, '零食', '20181205/12c8a6f4f1b239a69334befa79df7cb8.jpg', '100', '2018-12-04'),
(10, '水果', '20181205/43deb4c004fe5baa2889b1fff33c94e7.jpg', '10', '2018-12-04'),
(11, '蔬菜', '20181205/7522d6382dc5009b26dfd0a09c33e47d.jpg', '5', '2018-12-04'),
(12, '家电', '20181205/dc05a6efe0a9660431a6c9ba68b0a8f4.jpg', '1000', '2018-12-04'),
(13, '风景', '20181205/u=4021013693,1692197727&fm=26&gp=0.jpg', '山水', '2018-12-05'),
(16, '苹果手机', '20181205/7522d6382dc5009b26dfd0a09c33e47d.jpg', 'R15', '2018-12-05'),
(17, '苹果手机', '20181205/abe68db6a8d4afce9ba2194e59cb8c2d.jpg', '12314', '2018-12-05'),
(18, '苹果手机', '20181205/164ee3d9d2574c5ef3eee38e21a9c69b.jpg', '5645614', '2018-12-05'),
(19, '苹果手机', '20181206/2b790f5b10f82fe338ff310453fd4cc9.jpg', '100', '2018-12-05'),
(20, '红米', '20181206/c8a951533a2563eb807374b3c3bbb3e9.jpg', '100', '2018-12-07');

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, '一级推荐奖励', '10'),
(2, '二级推荐奖励', '5'),
(3, '三级推荐奖励', '3');

-- --------------------------------------------------------

--
-- 表的结构 `img`
--

CREATE TABLE IF NOT EXISTS `img` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `src` varchar(100) NOT NULL,
  `url` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `img`
--

INSERT INTO `img` (`id`, `src`, `url`) VALUES
(1, 'http://pic1.nipic.com/2008-08-28/200882812418965_2.jpg', 'www.baidu.com'),
(2, 'http://pic1.nipic.com/2008-08-28/200882812418965_2.jpg', 'www.baidu.com'),
(3, '', '22');

-- --------------------------------------------------------

--
-- 表的结构 `orderform`
--

CREATE TABLE IF NOT EXISTS `orderform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `spec_id` int(11) NOT NULL COMMENT '商品规格id',
  `ware_num` int(11) NOT NULL COMMENT '商品数量',
  `addressid` int(11) NOT NULL COMMENT '地址id',
  `state` int(11) NOT NULL COMMENT '1.待付款2.代发货3.待收货4.取消单',
  `priceid` int(11) NOT NULL COMMENT '订单号id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='订单' AUTO_INCREMENT=101 ;

--
-- 转存表中的数据 `orderform`
--

INSERT INTO `orderform` (`id`, `user_id`, `spec_id`, `ware_num`, `addressid`, `state`, `priceid`) VALUES
(96, 14, 20, 1, 12, 2, 15),
(97, 14, 20, 1, 12, 2, 16),
(98, 14, 22, 1, 12, 2, 16),
(99, 14, 24, 1, 12, 2, 16),
(100, 14, 18, 1, 12, 1, 17);

-- --------------------------------------------------------

--
-- 表的结构 `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `personal_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` int(10) NOT NULL COMMENT '０：菜单，１：其他',
  `ords` int(10) NOT NULL COMMENT '排序',
  `pid` int(20) NOT NULL COMMENT '二级菜单',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `permission`
--

INSERT INTO `permission` (`id`, `name`, `personal_name`, `type`, `ords`, `pid`) VALUES
(1, 'admin/index/index', '首页', 0, 1, 0),
(2, 'admin/img/index', '轮播图管理', 0, 2, 0),
(4, 'admin/img/insert', '新增轮播图', 1, 1, 2);

-- --------------------------------------------------------

--
-- 表的结构 `priceform`
--

CREATE TABLE IF NOT EXISTS `priceform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `form_id` decimal(20,0) NOT NULL COMMENT '支付订单号',
  `back_id` decimal(29,0) NOT NULL COMMENT '第三方返回订单号',
  `price` decimal(10,2) NOT NULL COMMENT '订单总共价钱',
  `state` int(1) NOT NULL COMMENT '1.未付款,2.已付款3.退款',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='支付信息' AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `priceform`
--

INSERT INTO `priceform` (`id`, `user_id`, `form_id`, `back_id`, `price`, `state`, `time`) VALUES
(15, 14, 201844084181492217, 2018121922001416190500235017, 74.20, 2, 0),
(16, 14, 201880613785833121, 2018121922001416190500235018, 150.50, 2, 0),
(17, 14, 201823481422718614, 0, 76.40, 0, 1545269357);

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `role_name`) VALUES
(1, '超级管理员66'),
(2, '轮播图管理员'),
(3, '普通管理员'),
(4, '产品管理员');

-- --------------------------------------------------------

--
-- 表的结构 `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `role_id` int(10) NOT NULL,
  `permission_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(2, 1),
(1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `shopcart`
--

CREATE TABLE IF NOT EXISTS `shopcart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ware_id` int(11) NOT NULL COMMENT '商品id',
  `spec_id` int(11) NOT NULL COMMENT '属性id',
  `quantity` int(11) NOT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='购物车' AUTO_INCREMENT=121 ;

--
-- 转存表中的数据 `shopcart`
--

INSERT INTO `shopcart` (`id`, `user_id`, `ware_id`, `spec_id`, `quantity`) VALUES
(73, 14, 2, 0, 1),
(120, 14, 3, 22, 1);

-- --------------------------------------------------------

--
-- 表的结构 `spec_list`
--

CREATE TABLE IF NOT EXISTS `spec_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mould` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sub` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `spec` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type_id` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- 转存表中的数据 `spec_list`
--

INSERT INTO `spec_list` (`id`, `mould`, `sub`, `spec`, `type_id`, `user_id`) VALUES
(28, '测试2', '容量', '128G/256G/512G', '3', 0),
(29, '测试2', '颜色', '艾尔蓝/黑色/银色', '3', 0),
(32, '小米模板', '容量', '128G/256G/512G', '3', 0),
(33, '小米模板', '颜色', '艾尔蓝/绿色/红色', '3', 0),
(34, '三星模板', '容量', '128G/256G/512G', '3', 0),
(35, '三星模板', '颜色', '艾尔蓝/幻影绿', '3', 0);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `user` varchar(15) NOT NULL COMMENT '用户账号',
  `pwd` varchar(33) NOT NULL,
  `email` varchar(20) NOT NULL,
  `point` int(10) NOT NULL COMMENT '积分',
  `pho` varchar(13) NOT NULL,
  `wechat_id` varchar(50) NOT NULL,
  `weibo_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `pid`, `user`, `pwd`, `email`, `point`, `pho`, `wechat_id`, `weibo_id`) VALUES
(4, 0, 'whiteComet', 'ee10a84856c78242e57515907c518a48', '2933586012@qq.com', 0, '13717383399', '', ''),
(6, 0, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1846052934@qq.com', 40, '13138872281', '', ''),
(11, 9, 'ff', 'e10adc3949ba59abbe56e057f20f883e', '', 20, '', '', ''),
(12, 11, 'gg', 'e10adc3949ba59abbe56e057f20f883e', '', 0, '', '', ''),
(14, 0, 'tuulice', 'e10adc3949ba59abbe56e057f20f883e', '540429762@qq.com', 0, '15625755639', '', '1738877520'),
(15, 6, 'xuan', 'e10adc3949ba59abbe56e057f20f883e', '', 0, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ware_spec`
--

CREATE TABLE IF NOT EXISTS `ware_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mould_name` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `ware_id` int(11) NOT NULL,
  `spec` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `ware_spec`
--

INSERT INTO `ware_spec` (`id`, `mould_name`, `user_id`, `ware_id`, `spec`, `price`, `stock`) VALUES
(16, '三星模板', 0, 2, '128G/艾尔蓝', 10.00, 50),
(17, '三星模板', 0, 2, '128G/幻影绿', 12.50, 62),
(18, '三星模板', 0, 2, '256G/艾尔蓝', 76.40, 45),
(19, '三星模板', 0, 2, '256G/幻影绿', 153.50, 49),
(20, '小米模板', 0, 3, '128G/艾尔蓝', 74.20, 50),
(21, '小米模板', 0, 3, '128G/绿色', 56.20, 67),
(22, '小米模板', 0, 3, '256G/艾尔蓝', 46.30, 45),
(23, '小米模板', 0, 3, '256G/绿色', 41.90, 49),
(24, '三星模板', 0, 4, '128G/艾尔蓝', 30.00, 50),
(25, '三星模板', 0, 4, '128G/幻影绿', 35.00, 67),
(26, '三星模板', 0, 4, '256G/艾尔蓝', 40.00, 45),
(27, '三星模板', 0, 4, '256G/幻影绿', 40.00, 49);

-- --------------------------------------------------------

--
-- 表的结构 `ware_type`
--

CREATE TABLE IF NOT EXISTS `ware_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `ware_type`
--

INSERT INTO `ware_type` (`id`, `type`) VALUES
(3, '手机');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
