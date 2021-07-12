#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: Users
#------------------------------------------------------------

CREATE TABLE Users(
        id          Int  Auto_increment PRIMARY KEY NOT NULL ,
        firstName   Varchar (50) NOT NULL ,
        lastName    Varchar (50) NOT NULL ,
        mail        Varchar (50) NOT NULL ,
        phone       Varchar (50),
        pwd         Varchar (255) NOT NULL ,
        img         Varchar (50),
        isAdmin     Boolean NOT NUll ,
        token       Varchar (255) 
);

#------------------------------------------------------------
# Table: Organization
#------------------------------------------------------------

CREATE TABLE Organization(
        id          Int  Auto_increment PRIMARY KEY NOT NULL ,
        orgName     Varchar (50) NOT NULL ,
        content     Varchar (50) NOT NULL ,
        orgUrl      Varchar (50) NOT NULL,
        orgMail     Varchar (50),
        phone       Varchar (50) ,
        backImg     Varchar (50)
);


#------------------------------------------------------------
# Table: Post
#------------------------------------------------------------

CREATE TABLE Post(
        id          Int  Auto_increment PRIMARY KEY NOT NULL ,
        title       Varchar (50) NOT NULL ,
        content     Varchar (50) NOT NULL ,
        img         Varchar (50),
        date        int NOT NULL
);

#------------------------------------------------------------
# Table: Tags
#------------------------------------------------------------

CREATE TABLE Tags(
        id               Int  Auto_increment PRIMARY KEY NOT NULL ,
        tagsName         Varchar (50) NOT NULL,
        id_Organization  Int NOT NULL ,
        FOREIGN KEY (id_Organization) REFERENCES Organization(id)
);

#------------------------------------------------------------
# Table: PostTags
#------------------------------------------------------------

CREATE TABLE PostTags (
        id_Post Int NOT NULL ,
        id_Tags Int NOT NULL,
    FOREIGN KEY (id_Post) REFERENCES Post(id),
    FOREIGN KEY (id_Tags) REFERENCES Tags(id)
);



#------------------------------------------------------------
# Table: OrgMember
#------------------------------------------------------------

CREATE TABLE OrgMember (
        id_Organization Int NOT NULL ,
        id_Users Int NOT NULL,
    FOREIGN KEY (id_Users) REFERENCES Users(id),
    FOREIGN KEY (id_Organization) REFERENCES Organization(id)
); 

#------------------------------------------------------------
# Table: OrgAdmin
#------------------------------------------------------------

CREATE TABLE OrgAdmin (
        id_Organization Int NOT NULL ,
        id_Users Int NOT NULL,
    FOREIGN KEY (id_Users) REFERENCES Users(id),
    FOREIGN KEY (id_Organization) REFERENCES Organization(id)
); 

#------------------------------------------------------------
# Table: UsersPost
#------------------------------------------------------------

CREATE TABLE UsersPost (
        id_Post Int NOT NULL ,
        id_Users Int NOT NULL,
    FOREIGN KEY (id_Users) REFERENCES Users(id),
    FOREIGN KEY (id_Post) REFERENCES Post(id)
); 

#------------------------------------------------------------
# Table: OrgPost
#------------------------------------------------------------

CREATE TABLE OrgPost (
        id_Post Int NOT NULL ,
        id_Organization Int NOT NULL,
    FOREIGN KEY (id_Organization) REFERENCES Organization(id),
    FOREIGN KEY (id_Post) REFERENCES Post(id)
); 


#------------------------------------------------------------
# Table: PostLike
#------------------------------------------------------------

CREATE TABLE PostLike (
        id_Post Int NOT NULL ,
        id_Users Int NOT NULL ,
        vote           BOOLEAN NOT NULL,
    FOREIGN KEY (id_Users) REFERENCES Users(id),
    FOREIGN KEY (id_Post) REFERENCES Post(id)
); 


#------------------------------------------------------------
# Table: PostComment
#------------------------------------------------------------

CREATE TABLE PostComment (
        id_Post Int NOT NULL ,
        id_Users Int NOT NULL ,
        commentaire    Varchar (255) NOT NULL ,
        date           Int NOT NULL,
    FOREIGN KEY (id_Users) REFERENCES Users(id),
    FOREIGN KEY (id_Post) REFERENCES Post(id)
); 

#------------------------------------------------------------
# Table: PostReport
#------------------------------------------------------------

CREATE TABLE PostReport (
        id_Post Int NOT NULL ,
        id_Users Int NOT NULL ,
        message    Varchar (255) NOT NULL ,
        date           Int NOT NULL,
    FOREIGN KEY (id_Users) REFERENCES Users(id),
    FOREIGN KEY (id_Post) REFERENCES Post(id)
);