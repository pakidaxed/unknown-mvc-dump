create table requests (
  id int(5) auto_increment primary key,
  first_name varchar(255) not null,
  last_name varchar(255) not null,
  email varchar(255) not null,
  phone varchar(255),
  subject_optional varchar(255),
  subject varchar(255) not null,
  message text not null,
  response text,
  seen boolean null
  )



