drop database if exists NeigeEtSoleil;
create database NeigeEtSoleil;
use NeigeEtSoleil;
 
create Table Users (
    idUser int auto_increment primary key,
    prenomUser varchar(50) not null,
    nomUser varchar(50) not null,
    usernameUser varchar(50) not null,
    passwordUser varchar(255) not null,
    mailUser varchar(100) not null,
    roleUser varchar(50) not null
);
 
create Table Gite (
    idGite int auto_increment primary key,
    nomGite varchar(100) not null,
    adresseGite varchar(150) not null,
    villeGite varchar(100) not null,
    codePostalGite int(10) not null,
    descriptionGite text not null,
    capaciteGite int not null,
    prixNuitGite decimal(10,2) not null,
    disponibiliteGite boolean not null,
    idUser int,
    foreign key (idUser) references Users(idUser)
);
 
create Table Reservation (
    idReservation int auto_increment primary key,
    dateDebutReservation date not null,
    dateFinReservation date not null,
    nomClient varchar(100) not null,
    prenomClient varchar(100) not null,
    mailClient varchar(100) not null,
    telephoneClient int(15) not null,
    idGite int,
    foreign key (idGite) references Gite(idGite),
    idUser int,
    foreign key (idUser) references Users(idUser)
);
 
create Table Favoris (
    idFavoris int auto_increment primary key,
    idUser int,
    foreign key (idUser) references Users(idUser),
    idGite int,
    foreign key (idGite) references Gite(idGite)
);