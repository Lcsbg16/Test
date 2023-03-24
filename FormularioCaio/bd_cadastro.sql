use cadastro;
create table pessoa (
id int unsigned not null auto_increment,
nome varchar(240) not null,
tipo_pessoa varchar(240) not null,
cpf varchar(240) not null,
data_nascimento date,
endereco varchar(240) not null,
bairro varchar(240) not null,
cep varchar(240) not null,
estado varchar(240) not null,
cidade varchar(240) not null,
telefone varchar(240) not null,
celular varchar(240) not null,
inscricao varchar(240) not null,
observacao varchar(240) not null,
data_criacao date not null,
data_atualizacao date not null,
PRIMARY KEY (id)
);




insert into pessoa
(nome, tipo_pessoa, cpf, data_nascimento, endereco,
bairro, cep, estado, cidade, telefone, celular, inscricao,
observacao, data_criacao, data_atualizacao)
values
('Caio','PF','926.167.600-61','1999-01-02',
'Joswaldo cesar','Marilea','28897018','RJ','Macabu', '9944-7993',
'22 89344-7667','2000123007','Branco', '2023-01-18','2024-01-18');

select * from pessoa;

insert into pessoa
(nome, tipo_pessoa, cpf, data_nascimento, endereco,
bairro, cep, estado, cidade, telefone, celular, inscricao,
observacao, data_criacao, data_atualizacao)
values
('Julia','PJ','926.167.600-61','1999-01-02',
'Joswaldo cesar','Marilea','28897018','ES','Macabu', '9944-7993',
'22 89344-7667','2000123007','Branco', '2023-01-18','2024-01-18');

