create table `failed_jobs`
(
    `id`         bigint unsigned not null auto_increment primary key,
    `uuid`       varchar(255)                        not null,
    `connection` text                                not null,
    `queue`      text                                not null,
    `payload`    longtext                            not null,
    `exception`  longtext                            not null,
    `failed_at`  timestamp default CURRENT_TIMESTAMP not null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
alter table `failed_jobs`
    add unique `failed_jobs_uuid_unique`(`uuid`)
create table `devices`
(
    `id`         bigint unsigned not null auto_increment primary key,
    `uid`        varchar(255) not null,
    `language`   varchar(255) not null,
    `os`         varchar(255) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
create table `device_apps`
(
    `device_id`  bigint unsigned not null,
    `app_id`     int          not null,
    `token`      varchar(255) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
alter table `device_apps`
    add constraint `device_apps_device_id_foreign` foreign key (`device_id`) references `devices` (`id`) on delete cascade
create table `purchases`
(
    `id`          bigint unsigned not null auto_increment primary key,
    `device_id`   bigint unsigned not null,
    `app_id`      int          not null,
    `receipt`     varchar(255) not null,
    `status`      tinyint(1) not null,
    `expire_date` timestamp    not null,
    `queued`      tinyint(1) not null default '0',
    `created_at`  timestamp null,
    `updated_at`  timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
alter table `purchases`
    add constraint `purchases_device_id_foreign` foreign key (`device_id`) references `devices` (`id`) on delete cascade
