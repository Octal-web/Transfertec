<div align="center">
  <h1>Transfertec</h1>
</div>

O projeto consistiu no desenvolvimento de um site institucional moderno e responsivo para a marca **Transfertec**, com foco em apresentar os segmentos que aborda. Em ambos segmentos, é representado sobre a história, serviços e tudo mais que compõem a marca.
  
---

## Índice

- [Sobre](#sobre)
- [Visualização](#visualizacao)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Arquitetura do Projeto](#arquitetura-do-projeto)
- [Como Executar o Projeto](#como-executar-o-projeto)

---

<h2 id="sobre">Sobre:</h2>

Através do painel de gerenciamento (manager) para cada segmento, é possível:

- Gerenciar conteúdos de todas as páginas daquele segmento
- Configurar SEO para cada página
- Alterar a política de privacidade
- Alterar conteúdos para cada idioma, sendo eles: inglês, espanhol e português - BR


E através do site para o público:

- Visualizar as páginas:
    - Segmento vinho:
        - **Home**: apresenta um pouco sobre as soluções, compromisso, produtos e parceiros
        - **Sobre**: conta sobre a história da marca, valores e tudo que a compõe 
        - **Equipamentos**: traz os equipamentos separados por tipo
        - **Insumos e Produtos**: assim como os equipamentos, traz os produtos e insumos no mesmo formato
        - **Engenharia**: apresenta sobre o processo que envolve esse segmento
        - **Contato**: localidade, informações e formulário para envio de email
    - Segmento engenharia:
        - **Home**: apresenta um pouco sobre a experiência, projetos e clientes
        - **Sobre**: conta sobre a história da marca, valores e tudo que a compõe 
        - **Setores de Atuação**: dividido por indústrias, cada uma traz um resumo daquele segmento
        - **Serviços**: apresenta o processo de solução e video do processo
        - **News**: separado por categorias, cada uma traz uma notícia mais recente
        - **Contato**: localidade, informações e formulário para envio de email

---


<h2 id="visualizacao">Visualização:</h2>

<img width="400" alt="image segmentos" src="https://github.com/user-attachments/assets/a6d2d4be-f6ce-4939-a0a6-bf2cc324f735" />
<img width="400" alt="image banner vinho segmento" src="https://github.com/user-attachments/assets/bb502114-5cac-4764-9c2d-d9e6baf7cfc7" />
<img width="400" alt="image banner outros segmentos" src="https://github.com/user-attachments/assets/f4468f86-1442-43be-bace-12a7c41c5652" />
<img width="400" alt="image footer vinho segmento" src="https://github.com/user-attachments/assets/00820651-7d73-4770-9761-15fd1f191791" />

---

<h2 id="tecnologias-utilizadas">Tecnologias Utilizadas:</h2>

### Back-end:
- **Laravel (^12.0)**: framework PHP para construção do projeto, gerenciamento de rotas, autenticação e etc.
- **PHP (^8.2)**: linguagem de desenvolvimento
- **Laravel Sanctum (^4.0)**: autenticação e proteção de rotas
- **Inertia.js (^2.0)**: integração entre backend Laravel e frontend React sem necessidade de API tradicional
- **Laravel Localization (^2.2)**: gerenciamennto de idiomas e rotas traduzidas
- **Ziggy (^2.0)**: compartilhamento de rotas Laravel diretamente no frontend React
- **Laravel Breeze (^2.2)**: estrutura inicial de autenticação e gerenciamento de usuários
- **Laravel Tinker (^2.9)**: ferramenta para testes e execução de comandos no ambiente
- **Laravel PT-BR Validator (*)**: validações adaptadas para formato brasileiro
- **Laravel Sail (^1.41)**: ambiente Docker para desenvolvimento local

### Front-end:
- **React (^18.2.0)**: biblioteca para construção de interfaces
- **Inertia React (^2.0.0)**: integração entre Laravel e React sem necessidade de API REST tradicional
- **Vite (^6.2.4)**: ferramenta de build e desenvolvimento rápido
- **Laravel Vite Plugin (^1.2.0)**: integração entre Laravel e Vite
- **Tailwind (^3.2.1)**: framework para estilização
- **Tailwind Forms (^0.5.3)**: plugin para estilização de formulários
- **PostCSS (^8.4.31)**: processador de CSS usado junto do Tailwind

### UI e experiência do usuário:
- **Font Awesome React (^0.2.2)**: biblioteca de ícones para interface
- **Headless UI (^2.0.0)**: componentes acessíveis e sem estilos pré-definidos
- **Swiper (^11.2.6)**: criação de sliders e carrosseis
- **Gsap (^3.12.7)**: biblioteca para animações
- **Lenis (^1.0.42)**: implementação de scroll suave
- **React Select (^5.10.1)**: select customizado

### Tabelas, dados e formulários:
- **React Input Mask (^2.0.4)**: máscaras para inputs como CPF e telefones
- **React SortableJS (^6.1.4)**: drag and drop para ordenação de elementos

### Upload e manipulação de arquivos:
- **React Dropzone (^14.3.8)**: upload de arquivos via drag and drop
- **React Image Crop (^11.0.7)**: recorte de imagens no navegador
- **browser-image-compression (^2.0.2)**: compressão de imagens

### Editor de texto:
- **Tiptap (^2.11.7)**: editor de texto altamente cuustimizável
- Extensões utilizadas:
  - **Image**: suporte para imagens
  - **Link**: gerenciamento de links
  - **Underline**: sublinhado no texto
  - **Table**: criação de tabelas
  - **Table Row:** gerenciamento de linhas
  - **Table Header**: cabeçalhos de tabelas
  - **Table Cell**: células de tabelas
  - **Text**: manipulação de conteúdo textual
  - **List Item**: manipulação de listas
  - **Figure Extension**: suporte a figuras
  - **Starter Kit**: funcionalidades básicas do editor
 

---

<h2 id="arquitetura-do-projeto">Arquitetura principal do Projeto:</h2>

```bash
Transfertec
│
├── app
│   ├── Http
│   │   ├── Controllers    # Controladores responsáveis pelas requisições e retornar respostas (separado por Engenharia e Enologia)
│   │   ├── Middleware     # Interceptação, autenticação e tratamento de requisições
│   │   ├── Requests       # Validação e autorização de formulários e requisições (separado por Engenharia e Enologia)
│   ├── Models             # Representação das tabelas do banco (Eloquent)
│   ├── Providers          # Configuração de pacotes
├── bootstrap              # Inicialização do framework
├── config                 # Arquivos de configuração
├── database               # Migrations, seeds e factories
├── public                 # Diretório público acessível pelo navegador
├── resources              # Frontend e recursos
│   ├── css                # Estilização 
│   ├── js                 # Componentes, páginas, hooks e layouts (separados por Engenharia e Enologia)
│   ├── views              # Templates e views do Laravel/Inertia
├── routes                 # Definição das rotas web (separados por Engenharia e Enologia)
├── storage                # Arquivos gerados (logs, cache e etc.)
├── tests
│

```

---

<h2 id="como-executar-o-projeto">Como Executar o Projeto:</h2>

1. Clone o repositório:

```bash
git clone https://github.com/Octal-web/Transfertec.git
cd Transfertec
```

2. Instale as dependências do Front-end:

```bash
npm install
```

3. Instale as dependências do Back-end:

```bash
composer install
```

4. Configure o ambiente

Crie o arquivo .env:

```bash
cp .env.example .env
```

Gere a chave da aplicação:
```bash
php artisan key:generate
```

Configure o banco de dados SQL e preencha com o acesso no .env

5. Rode o projeto:
```bash
npm run dev
php artisan serve
```


