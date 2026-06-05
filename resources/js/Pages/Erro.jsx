import { Head, Link } from "@inertiajs/react";

const getErrorMessage = (status, url) => {
    const messages = {
        503: "Desculpe, estamos em manutenção. Volte em breve.",
        500: "Ops, algo deu errado em nossos servidores.",
        404: `Desculpe, a página que você está procurando "<strong>${url}</strong>" não foi encontrada.`,
        403: `Você não tem permissão para acessar esta página: <strong>${url}</strong>.`,
    };

    return messages[status] || "Ocorreu um erro desconhecido.";
};

const items = {
    eng: {
        bg: "bg-eng-primary",
        logo: "/eng/site/img/logo.png",
        favicon: "/eng/favicon.ico",
    },
    eno: {
        bg: "bg-eno-secondary",
        logo: "/eno/site/img/logo.png",
        favicon: "/eno/favicon.ico",
    },
};

const Erro = ({ status }) => {
    const path = window.location.pathname;
    const isEnologia = path.startsWith("/enologia");

    const brand = isEnologia ? "eno" : "eng";
    const theme = items[brand];

    const handleRedirect = () => {
        const isEnologiaManager = path.startsWith("/enologia/manager");
        const isEngenhariaManager = path.startsWith("/engenharia/manager");

        if (isEngenhariaManager) {
            return "Engenharia.Manager.Home.index";
        }

        if (isEnologiaManager) {
            return "Enologia.Manager.Home.index";
        }

        return isEnologia ? "Enologia.Home.index" : "Engenharia.Home.index";
    };

    return (
        <>
            <Head>
                <title>Transfertec | Error</title>
                <link rel="icon" href={theme.favicon} type="image/x-icon" />
            </Head>

            <main className="min-h-screen flex items-center justify-center container max-w-large">
                <div className="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
                    <div
                        className={`absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full ${theme.bg} opacity-20 blur-[100px]`}
                    ></div>
                </div>
                <div className="text-center">
                    <img
                        src={theme.logo}
                        alt="Logo"
                        className="mx-auto block max-xl:max-w-40 max-w-[100%] mb-10 invert"
                    />
                    <h1 className="text-9xl md:text-[300px] font-bold">
                        {status}
                    </h1>

                    <p
                        className="text-base md:text-xl mb-20 text-custom-gray"
                        dangerouslySetInnerHTML={{
                            __html: getErrorMessage(status, path),
                        }}
                    />

                    <Link
                        href={route(handleRedirect())}
                        className={`inline-block px-6 py-3 text-xl font-medium rounded-lg transition-all hover:scale-105 hover:shadow text-white ${theme.bg}`}
                    >
                        Voltar
                    </Link>
                </div>
            </main>
        </>
    );
};

export default Erro;
