create table entities
(
    id     int auto_increment
        primary key,
    name   varchar(255)                    not null,
    type   tinyint(1) unsigned             not null,
    active tinyint(1) unsigned default '1' not null,
    constraint uniq_name
        unique (name, type)
);

create index name
    on entities (name);

create index type
    on entities (type);

insert into entities (name, type) values ('test', 2);

create table labels
(
    id        int auto_increment
        primary key,
    name      varchar(255)                  not null,
    active    tinyint(1) unsigned default 1 not null,
    entity_id int                           not null,
    constraint uniq_name
        unique (name),
    constraint labels_ibfk_1
        foreign key (entity_id) references entities (id)
            on delete cascade
);

create index name
    on labels (name);

create index entity_id
    on labels (entity_id);
