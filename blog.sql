-- 用户表
create table admin (
    `user_id` int unsigned not null auto_increment primary key comment 'PK',
    `username` varchar(64) not null default '' comment '用户名',
    `password` char(32) not null default '' comment '密码MD5',
    `salt` CHAR(32) NOT NULL DEFAULT '' COMMENT '密码混肴的字符串',
    `nickname` varchar(64) not null default '' comment '昵称，用于显示的名称',
    `logo` varchar(255) not null default '',
    `user_stataus` TINYINT(3)  DEFAULT 1 COMMENT'用户状态,1:正常,2:24小时内禁止登录.0:销号状态',
    last_login int UNSIGNED DEFAULT 0 COMMENT '上次登录时间',
    `create_at` int not null default 0 COMMENT '创建账号的时间',
    update_at int not null default 0 comment '更新时间',
    UNIQUE (username)
) charset=utf8 ENGINE =innodb COMMENT '管理员表';

-- 访问ip记录表
create table access_ip(
    id int UNSIGNED not null AUTO_INCREMENT PRIMARY KEY COMMENT 'pk',
    ip int UNSIGNED NOT NULL DEFAULT 0 COMMENT '访问者的ip',
    user_id int UNSIGNED  DEFAULT 0 COMMENT '用户的id',
    `login_num` TINYINT(3)  DEFAULT 0 COMMENT '记录本次登录请求次数,最大值为5',
    ip_status TINYINT(3) DEFAULT 1 NOT NULL COMMENT 'ip状态,1为正常,0为危险',
    access_time int UNSIGNED DEFAULT 0 COMMENT '访问时间',
    update_at int not null default 0 comment '更新时间',
    `create_at` int not null default 0 COMMENT '创建时间'
)CHARSET = utf8 ENGINE  =innodb COMMENT '访问ip记录表';

-- 文章表
create table article(
    id int unsigned not null AUTO_INCREMENT primary key comment 'pk',
    title varchar(80) not null comment '文章标题'
    #######未完成

)CHARSET = utf8 ENGINE = innodb COMMENT '文章表';
