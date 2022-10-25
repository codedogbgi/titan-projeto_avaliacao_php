# Teste Rafael Esposo

###Rodar teste com Docker

- Instale o Docker
- Navegue pelo terminal até a pasta raiz do projeto
  - Execute o comando `docker-compose up -d`
  - Aguarde a subida dos containers
- Acesse em seu navegador a url `http://titansoftware.localhost/`

---

###Rodar teste em ambiente comum

- Copie todo o conteudo da pasta `www` para dentro da pasta raiz do servidor
- Efetue a troca dos dados de acesso do servidor MySQL no arquivo `global.php`

      <?php
      	$hostname = 'mysql-1';
      	$username = 'root';
      	$password = '123456';
      	$database = 'titansoftware';

- Acesse o banco de dados do servidor e execute o seguinte script

```sql
CREATE TABLE tbl_empresa (
    id_empresa INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_empresa)
);

CREATE TABLE tbl_conta_pagar (
    id_conta_pagar INT NOT NULL AUTO_INCREMENT,
    valor DECIMAL(10,2) NOT NULL,
    data_pagar DATE NOT NULL,
    pago tinyint(1) DEFAULT 0,
    id_empresa INT NOT NULL,
    CONSTRAINT PK_tbl_usuario PRIMARY KEY (id_conta_pagar),
    FOREIGN KEY (id_empresa) REFERENCES tbl_empresa(id_empresa)
);
```

---

### Pronto!

##### Espero que tenham gostado, qualquer dúvida ou problema estou a disposição

---

# Avaliacao-PHP-MYSQL

## O projeto consiste em análisar o conhecimento nas seguintes técnologias:

- PHP Orientado a Objetos
- Arquiteura MVC
- PDO com MySql
- Javascript ou JQuery

## _Obs.: Favor enviar junto com o projeto o script da criação das tabelas._