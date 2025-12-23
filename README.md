# Moodle

<p align="center"><a href="https://moodle.org" target="_blank" title="Moodle Website">
  <img src="https://raw.githubusercontent.com/moodle/moodle/main/.github/moodlelogo.svg" alt="The Moodle Logo">
</a></p>

[Moodle][1] is the World's Open Source Learning Platform, widely used around the world by countless universities, schools, companies, and all manner of organisations and individuals.

Moodle is designed to allow educators, administrators and learners to create personalised learning environments with a single robust, secure and integrated system.

## Documentation

- Read our [User documentation][3]
- Discover our [developer documentation][5]
- Take a look at our [demo site][4]

## Community

[moodle.org][1] is the central hub for the Moodle Community, with spaces for educators, administrators and developers to meet and work together.

You may also be interested in:

- attending a [Moodle Moot][6]
- our regular series of [developer meetings][7]
- the [Moodle User Association][8]

## Installation and hosting

Moodle is Free, and Open Source software. You can easily [download Moodle][9] and run it on your own web server, however you may prefer to work with one of our experienced [Moodle Partners][10].

Moodle also offers hosting through both [MoodleCloud][11], and our [partner network][10].

## License

Moodle is provided freely as open source software, under version 3 of the GNU General Public License. For more information on our license see

[1]: https://moodle.org
[2]: https://moodle.com
[3]: https://docs.moodle.org/
[4]: https://sandbox.moodledemo.net/
[5]: https://moodledev.io
[6]: https://moodle.com/events/mootglobal/
[7]: https://moodledev.io/general/community/meetings
[8]: https://moodleassociation.org/
[9]: https://download.moodle.org
[10]: https://moodle.com/partners
[11]: https://moodle.com/cloud
[12]: https://moodledev.io/general/license

# Integração Moodle + API Externa em PHP

Este repositório documenta a instalação do Moodle localmente e o desenvolvimento de uma **API externa em PHP** destinada a ser consumida pelo Moodle via **Web Services (REST)**.

O objetivo é demonstrar uma integração real entre um LMS robusto (Moodle) e um backend personalizado desenvolvido em PHP.

## ✔ Status do Projeto
- Moodle instalado localmente em `C:\xampp\htdocs\moodle`
- API externa em PHP criada (CRUD de Cursos e Alunos)
- Integração Moodle ↔ API externa **em desenvolvimento**
- Próximos passos: criação de plugin local, registro de funções externas e consumo via token REST

## 📌 Objetivo
Demonstrar:
- Instalação e configuração do Moodle
- Criação de API REST em PHP (PDO + MySQL)
- Integração entre sistemas usando Web Services
- Estruturação de um ambiente de estudo e testes para LMS corporativo/educacional

## 📂 Estrutura do Repositório
- `/api-escola/` — Código da API externa (PHP + MySQL)
- `/docs/` — Prints da instalação e estrutura do Moodle
- `README.md` — Documentação do projeto

## 🧩 Sobre o Moodle
O Moodle **não está incluído** neste repositório por ser um software de terceiros.  
Ele foi instalado localmente em:
```
http://localhost/moodle
```


---

## ✨ Autor  
**Isaias Lourenço © Vetor256**  
🌐 <a href="https://vetor256.com" target="_blank" rel="noopener noreferrer">vetor256.com</a>