-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Fev-2021 às 23:55
-- Versão do servidor: 10.4.14-MariaDB
-- versão do PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairros`
--

CREATE TABLE `bairros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `bairros`
--

INSERT INTO `bairros` (`id`, `nome`, `cidade_id`, `created_at`, `updated_at`) VALUES
(1, 'Centro', 1, '2021-02-28 21:21:54', '2021-02-28 21:22:31');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cidades`
--

CREATE TABLE `cidades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `populacao` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cidades`
--

INSERT INTO `cidades` (`id`, `nome`, `populacao`, `created_at`, `updated_at`) VALUES
(1, 'Cascavel', 62850001, '2021-02-28 21:14:46', '2021-02-28 21:14:55');

-- --------------------------------------------------------

--
-- Estrutura da tabela `dispositivos`
--

CREATE TABLE `dispositivos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mac` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distribuidor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `dispositivos`
--

INSERT INTO `dispositivos` (`id`, `nome`, `code`, `mac`, `ip`, `distribuidor_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Maquineta 1', NULL, NULL, NULL, 32, '2021-02-28 22:48:54', '2021-02-28 22:51:14', NULL),
(3, 'Maquineta 2', NULL, NULL, NULL, 16, '2021-02-28 22:51:24', '2021-02-28 22:51:24', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `distribuidores`
--

CREATE TABLE `distribuidores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cep` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condominio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unidade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bloco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `distribuidores`
--

INSERT INTO `distribuidores` (`id`, `usuario_id`, `cep`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `condominio`, `unidade`, `bloco`, `latitude`, `longitude`, `nome`, `email`, `cpf`, `imagem`, `telefone`, `data_nascimento`, `created_at`, `deleted_at`, `updated_at`, `rg`, `area`) VALUES
(16, 34, '03134002', 'Rua Ibitirama', '2130', '125A', 'Vila Prudente', 'São Paulo', 'SP', NULL, NULL, NULL, '-2.9894621', '-59.9861238', 'Edinei reis', 'Edinei@adn23.com.br', '35266116889', NULL, '(11) 98197-4276', '1987-11-08', '2020-06-12 17:27:55', NULL, '2020-06-12 17:27:55', NULL, NULL),
(17, 35, '60714152', 'A', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', NULL, NULL, NULL, '-2.9894621', '-59.9861238', 'Helpty', 'Helpty@teste.com', '80808359002', NULL, '(85) 98628-8861', '1997-08-13', '2020-06-12 21:26:39', NULL, '2020-06-12 21:26:39', NULL, NULL),
(18, 36, '60714152', 'Rua Alemanha', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', NULL, NULL, NULL, '-2.9894621', '-59.9861238', 'Orlando de sousa', 'Helpty@adn23.com', '28615695067', NULL, '(85) 98628-8861', '1997-08-13', '2020-06-12 21:31:36', NULL, '2020-06-12 21:31:36', NULL, NULL),
(19, 37, '60714152', 'Rua Alemanha', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', 'Condomínio das flores', '2', 'A', '-2.9894621', '-59.9861238', 'Sara rocha', 'Teste@helpty.com', '36594831097', NULL, '(85) 98628-8861', '1998-08-27', '2020-06-12 21:45:18', NULL, '2020-06-12 21:45:18', NULL, NULL),
(20, 38, '60714152', 'Rua Alemanha', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', 'Condomínio das araucarias', '1', NULL, '-2.9894621', '-59.9861238', 'Help teste', 'Helpty2@teste.com', '16965319003', NULL, '(85) 98628-8861', '1997-08-13', '2020-06-12 22:09:29', NULL, '2020-06-12 22:09:29', NULL, NULL),
(22, 40, '01153020', 'Rua Sousa Lima', '86', NULL, 'Barra Funda', 'São Paulo', 'SP', 'Vila real', '103', 'A', '-2.9894621', '-59.9861238', 'Arnaldo teste', 'arnaldo@devolus.com', '37440851048', NULL, '(11) 97096-5017', '1977-06-08', '2020-06-12 23:44:22', NULL, '2020-06-12 23:44:22', NULL, NULL),
(23, 41, '08021170', 'Rua Ernesto Evans', '21', NULL, 'Vila Rosaria', 'São Paulo', 'SP', 'Xuz', '21A', 'A', '-2.9894621', '-59.9861238', 'Edinei', 'Edinei.nsr@hmail.com', '01234567890', NULL, '(11) 97018-2424', '1965-11-08', '2020-06-16 03:04:08', NULL, '2020-06-16 03:04:08', NULL, NULL),
(24, 42, '13097105', 'Rua Thomas Nilsen Júnior', '159', NULL, 'Parque Imperador', 'Campinas', 'SP', 'V Hera', '18', 'Na', '-2.9894621', '-59.9861238', 'daniel leite', 'Daniel.leite@prosecurity.com.br', '09433004795', NULL, '(19) 99712-5665', '1980-12-04', '2020-06-16 03:12:11', NULL, '2020-12-16 19:17:07', NULL, NULL),
(25, 43, '60714152', 'Rua Alemanha', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', 'Golden', '2', 'B', '-3.784818', '-38.554634', 'prestador teste', 'Prestador@helpty.com', '98706673090', NULL, '(85) 98628-8864', '1997-08-13', '2020-06-17 03:13:47', NULL, '2020-06-17 03:13:47', NULL, NULL),
(26, 44, '60190210', 'Alameda Maria Tereza', '123', NULL, 'Cidade 2000', 'Fortaleza', 'CE', 'Casa', '6', 'A', '-3.7502512', '-38.4705064', 'Mauricio holanda', 'mauricio.holanda@scafe.com', '60660606066', NULL, '(85) 99999-9999', '1996-07-04', '2020-06-17 23:17:45', NULL, '2020-06-17 23:17:45', NULL, NULL),
(27, 45, '60190210', 'Alameda Maria Tereza', '123', NULL, 'Cidade 2000', 'Fortaleza', 'CE', 'Teste', '5', 'Teste', '-3.7502512', '-38.4705064', 'Mauricio freitas', 'mauricio@scafe.com', '60643240314', NULL, '(85) 99999-9999', '1996-07-04', '2020-06-17 23:23:55', NULL, '2020-06-17 23:23:55', NULL, NULL),
(28, 46, '13097105', 'Rua Thomas Nilsen Júnior', '220', NULL, 'Parque Imperador', 'Campinas', 'SP', 'Xpto', '20', '2', '-22.8475515', '-47.0277635', 'Daniel Leite', 'Daniel1.leite@prosecurity.com.br', '09433004795', NULL, '(19) 99712-5668', '1980-12-04', '2020-06-17 23:34:05', NULL, '2020-06-18 20:39:28', NULL, NULL),
(29, 47, '60714152', 'Rua Alemanha', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', 'Royal', '3', 'B', '-3.784818', '-38.554634', 'Marcos Soares', 'Prestador1@helpty.com', '69744988029', NULL, '(85) 98628-8861', '1997-08-13', '2020-06-18 07:38:37', NULL, '2020-06-18 07:38:37', NULL, NULL),
(30, 48, '60714152', 'Rua Alemanha', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', 'Royal', '1', 'B', '-3.784818', '-38.554634', 'Luiz Felipe', 'Luiz@helpty.com', '91921113006', NULL, '(85) 98628-8861', '1997-08-13', '2020-06-18 08:01:04', NULL, '2020-06-18 08:01:04', NULL, NULL),
(31, 52, '60714152', 'Rua Alemanha', '777', NULL, 'Itaperi', 'Fortaleza', 'CE', 'Royal', '3', 'B', '-3.784818', '-38.554634', 'Vitor Veras (Cliente)', 'Vitor@cliente.com', '85893538005', NULL, '(85) 98628-8861', '1997-08-13', '2020-06-18 23:16:27', NULL, '2020-06-18 23:16:27', NULL, NULL),
(32, 53, '01153020', 'Rua Sousa Lima', '86', NULL, 'Barra Funda', 'São Paulo', 'SP', 'Versati', '1305', '2', '-23.5286258', '-46.6539577', 'Arnaldo Cavalcanti', 'contato@adn23.com.br', '15497846092', 'uz0Wff210o30CPYo9iOx1614465281.jpg', '(11) 97096-5017', '1977-06-08', '2020-07-15 18:49:14', NULL, '2021-02-27 22:34:41', '321542135464', 'quebradas');

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa`
--

CREATE TABLE `empresa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `main_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contracted_menu_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_background` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_maps_api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_server_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_driver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_encryption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descontos` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `empresa`
--

INSERT INTO `empresa` (`id`, `nome`, `cnpj`, `created_at`, `updated_at`, `deleted_at`, `main_logo`, `favicon`, `menu_logo`, `contracted_menu_logo`, `menu_background`, `menu_color`, `google_maps_api_key`, `fcm_server_key`, `mail_driver`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from_name`, `mail_from_address`, `descontos`) VALUES
(1, 'Retaguarda POS', '96231568000192', '2020-01-28 01:26:54', '2021-02-28 17:21:23', NULL, 'CWWtvf1y6AqnEaW0FTeJ1614454400.png', 'wqwmYC6G9TdwrJHsxEsC1614454400.png', 'HX4yhy9UBkPIUKnjX1SP1614454400.png', '6k82j5190lOdRJQEK4NR1614454400.png', '#808080', '#ffffff', 'AIzaSyAk3wdsEopwU_B5lRgkxdeUzBx-c2PoLMQ', 'AAAAYMFVij0:APA91bHhMsAxF_lSAVKgngHKJTZqNxhZ4pCWxLpOLhN8tMEgs5C9ivd9iuPJVpk62bdqJwkK_2PZwNfqfHqz8PGrUosrA3SdPfJ6FrfgJEbChmMOnDgJKN5qDvqd4nnFZpj2ddOswPgF', 'smtp', 'mail.adn23.com.br', '587', 'naoresponda@adn23.com.br', 'Nao@Resp12', NULL, 'Retaguarda POS', 'naoresponda@retaguardapos.com.br', '[{\"servico_id\":\"1\",\"diasemana\":\"terca\",\"inicio\":\"14:00\",\"fim\":\"17:00\",\"desconto\":\"25,00\"},{\"servico_id\":\"1\",\"diasemana\":\"quarta\",\"inicio\":\"14:00\",\"fim\":\"17:00\",\"desconto\":\"25,00\"},{\"servico_id\":\"1\",\"diasemana\":\"quinta\",\"inicio\":\"14:00\",\"fim\":\"17:00\",\"desconto\":\"25,00\"},{\"servico_id\":\"3\",\"diasemana\":\"terca\",\"inicio\":\"14:00\",\"fim\":\"17:00\",\"desconto\":\"25,00\"},{\"servico_id\":\"3\",\"diasemana\":\"quarta\",\"inicio\":\"14:00\",\"fim\":\"17:00\",\"desconto\":\"25,00\"},{\"servico_id\":\"3\",\"diasemana\":\"quinta\",\"inicio\":\"14:00\",\"fim\":\"17:00\",\"desconto\":\"25,00\"}]');

-- --------------------------------------------------------

--
-- Estrutura da tabela `etapas`
--

CREATE TABLE `etapas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `etapas`
--

INSERT INTO `etapas` (`id`, `descricao`, `data`, `created_at`, `updated_at`) VALUES
(2, 'POS', '2021-02-28', '2021-02-28 22:49:15', '2021-02-28 22:49:15'),
(3, 'Rua', '2021-02-28', '2021-02-28 22:51:06', '2021-02-28 22:51:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `matrizes`
--

CREATE TABLE `matrizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bilhete` int(11) NOT NULL,
  `combinacoes` char(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `menus`
--

INSERT INTO `menus` (`id`, `permission`, `parent`, `ordem`, `icon`, `label`, `link`, `created_at`, `updated_at`) VALUES
(8, 'usuarios-listar', NULL, NULL, 'fa fa-user-plus', 'Usuários', 'usuarios', '2021-02-28 15:37:12', '2021-02-28 15:37:12'),
(9, 'distribuidores-listar', NULL, NULL, 'fa fa-money', 'Distribuidores', 'distribuidores', '2021-02-28 15:38:01', '2021-02-28 15:38:01'),
(10, 'cidades-listar', 12, 1, NULL, 'Cidades', 'cidades', '2021-02-28 20:49:30', '2021-02-28 20:55:04'),
(11, 'bairros-listar', 12, 2, NULL, 'Bairros', 'bairros', '2021-02-28 20:49:50', '2021-02-28 20:55:09'),
(12, 'pontos-listar', NULL, NULL, 'fa fa-map-marker', 'Pontos', NULL, '2021-02-28 20:50:16', '2021-02-28 20:54:42'),
(13, 'pontos-listar', 12, 3, NULL, 'Pontos', 'pontos', '2021-02-28 20:54:02', '2021-02-28 20:55:15'),
(14, 'matrizes-listar', NULL, NULL, 'fa fa-barcode', 'Matrizes', 'matrizes', '2021-02-28 21:56:12', '2021-02-28 21:56:12'),
(15, 'etapas-listar', NULL, NULL, 'fa fa-bars', 'Etapas', 'etapas', '2021-02-28 22:06:12', '2021-02-28 22:06:12'),
(16, 'dispositivos-listar', NULL, NULL, 'fa fa-credit-card', 'Dispositivos', 'dispositivos', '2021-02-28 22:27:23', '2021-02-28 22:27:23'),
(17, 'vendas-listar', NULL, NULL, 'fa fa-usd', 'Vendas', 'vendas', '2021-02-28 22:47:36', '2021-02-28 22:47:36');

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2020_01_21_210954_soft_delete_users', 1),
(4, '2020_01_23_141057_user_api_token', 1),
(5, '2020_01_23_144414_empresa', 1),
(6, '2020_01_23_145423_perfil', 1),
(7, '2020_01_25_205837_permissions', 2),
(8, '2020_01_25_205838_user_perfil_empresa', 2),
(9, '2020_01_27_195106_usercpf', 2),
(10, '2020_01_27_215641_servico', 2),
(11, '2020_01_28_132259_configempresa', 3),
(12, '2020_01_29_122913_imagemperfil', 4),
(13, '2020_01_29_143451_prestador', 4),
(14, '2020_01_29_143943_create_prestador_servico_table', 4),
(15, '2020_01_30_150330_novoscamposprestador', 5),
(16, '2020_01_30_172305_prestadorreferencia', 5),
(17, '2020_01_30_172321_prestadorbanco', 5),
(18, '2020_01_30_172350_prestadordocumento', 5),
(19, '2020_02_03_130040_camposservico', 6),
(20, '2020_02_03_190546_camposprestadorservico', 6),
(21, '2020_02_04_180646_cliente', 7),
(22, '2020_02_05_132918_experienciaprestadorservico', 8),
(23, '2020_02_05_184104_filiacaoprestador', 8),
(24, '2020_02_07_124426_posservico', 9),
(25, '2020_02_11_201349_contratacao', 10),
(26, '2020_02_12_134733_contratacaoconcluida', 10),
(27, '2020_02_17_204424_contratacaofoto', 11),
(28, '2020_02_18_202652_fcmtoken', 12),
(29, '2020_04_09_162252_transaction_code_contratacao', 13),
(30, '2020_04_09_164647_create_payments_table', 13),
(31, '2020_04_09_165152_data_nascimento_cliente', 13),
(32, '2020_04_15_1101352_campospayments', 13),
(33, '2020_04_24_103852_descontoempresa', 14),
(34, '2020_06_03_141252_devicetokennull', 15),
(35, '2020_06_16_103852_cancelamentoprestadorcontratacao', 16),
(36, '2020_07_22_173352_feedbackclientecontratacao', 17),
(37, '2020_08_19_120852_recorrenciacontratacao', 18),
(38, '2020_09_11_163947_create_mural_table', 18),
(39, '2020_11_06_162043_create_faq_table', 19),
(40, '2020_12_16_102000_gorjetacontratacao', 20),
(41, '2021_02_27_185552_clientestodistribuidores', 21),
(42, '2021_02_27_191652_rg_area_distribuidor', 22),
(43, '2021_02_28_104705_create_menus_table', 23),
(44, '2021_02_28_165116_create_matrizs_table', 24),
(47, '2021_02_28_172338_create_cidades_table', 25),
(48, '2021_02_28_172833_create_bairros_table', 25),
(49, '2021_02_28_172919_create_pontos_table', 25),
(50, '2021_02_28_190040_create_etapas_table', 26),
(51, '2021_02_28_191203_create_dispositivos_table', 27),
(52, '2021_02_28_193736_create_vendas_table', 28);

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('tfranca2@gmail.com', '$2y$10$1VE6fcxIWU/rwgynQfWR.OyE1f0JT3hi6NQxhps.RIN4PUW0OceMS', '2020-11-06 13:32:44');

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil`
--

CREATE TABLE `perfil` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `perfil`
--

INSERT INTO `perfil` (`id`, `nome`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', NULL, NULL, NULL),
(2, 'Cliente', '2020-05-13 15:56:42', '2020-06-18 15:39:18', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `permissions`
--

INSERT INTO `permissions` (`id`, `role`, `perfil_id`, `created_at`, `updated_at`) VALUES
(209, 'faq-listar', 2, '2020-11-06 20:20:08', '2020-11-06 20:20:08'),
(210, 'faq-listar', 3, '2020-11-06 20:20:13', '2020-11-06 20:20:13'),
(551, 'empresas-listar', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(552, 'empresas-incluir', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(553, 'empresas-editar', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(554, 'empresas-excluir', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(555, 'empresas-gerenciar', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(556, 'usuarios-listar', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(557, 'usuarios-incluir', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(558, 'usuarios-editar', 1, '2021-02-28 22:47:45', '2021-02-28 22:47:45'),
(559, 'usuarios-excluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(560, 'usuarios-gerenciar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(561, 'perfis-listar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(562, 'perfis-incluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(563, 'perfis-editar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(564, 'perfis-excluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(565, 'perfis-gerenciar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(566, 'configuracoes-listar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(567, 'configuracoes-incluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(568, 'configuracoes-editar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(569, 'configuracoes-excluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(570, 'configuracoes-gerenciar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(571, 'bairros-listar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(572, 'bairros-incluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(573, 'bairros-editar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(574, 'bairros-excluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(575, 'bairros-gerenciar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(576, 'cidades-listar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(577, 'cidades-incluir', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(578, 'cidades-editar', 1, '2021-02-28 22:47:46', '2021-02-28 22:47:46'),
(579, 'cidades-excluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(580, 'cidades-gerenciar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(581, 'dispositivos-listar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(582, 'dispositivos-incluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(583, 'dispositivos-editar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(584, 'dispositivos-excluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(585, 'dispositivos-gerenciar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(586, 'distribuidores-listar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(587, 'distribuidores-incluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(588, 'distribuidores-editar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(589, 'distribuidores-excluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(590, 'distribuidores-gerenciar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(591, 'etapas-listar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(592, 'etapas-incluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(593, 'etapas-editar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(594, 'etapas-excluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(595, 'etapas-gerenciar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(596, 'matrizes-listar', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(597, 'matrizes-incluir', 1, '2021-02-28 22:47:47', '2021-02-28 22:47:47'),
(598, 'matrizes-editar', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(599, 'matrizes-excluir', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(600, 'matrizes-gerenciar', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(601, 'pontos-listar', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(602, 'pontos-incluir', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(603, 'pontos-editar', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(604, 'pontos-excluir', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(605, 'pontos-gerenciar', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(606, 'vendas-listar', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(607, 'vendas-incluir', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(608, 'vendas-editar', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(609, 'vendas-excluir', 1, '2021-02-28 22:47:48', '2021-02-28 22:47:48'),
(610, 'vendas-gerenciar', 1, '2021-02-28 22:47:49', '2021-02-28 22:47:49');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pontos`
--

CREATE TABLE `pontos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` tinyint(1) DEFAULT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsavel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_cnpj` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `funcionamento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encerramento` time DEFAULT NULL,
  `ponto_referencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distribuidor_id` int(11) DEFAULT NULL,
  `cidade_id` int(11) DEFAULT NULL,
  `bairro_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `token_fcm`
--

CREATE TABLE `token_fcm` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perfil_id` int(11) NOT NULL,
  `cpf` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa_id` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `api_token`, `perfil_id`, `cpf`, `imagem`, `empresa_id`, `password`, `remember_token`, `email_verified_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', 'admin@admin.com', 'CD2VTAsQ8WpsjNdW6crIn4KnmRfsTEm', 1, '13430162238', 'xrIPhqqiDXt5kkOfGIrK1614455158.png', 1, '$2y$10$OGnfhCGrP1K6C5IlqnXy4.CD2VTAsQ8WpsjNdW6crIn4KnmRfsTEm', 'gyiSGCZrE3QkCK3UjFwjOrSDavOgISPiDZyXmkTWHIUFryIB4S9AzBZN2BJY', NULL, NULL, '2021-02-27 22:35:23', NULL),
(2, 'Tiago França', 'tfranca2@gmail.com', NULL, 2, '96409281277', NULL, 1, '$2y$10$ULIPRfwvnvgIV0yfCv3rDOvXD64BK4chh2Mn24blZGqsxUY0FkY2y', NULL, NULL, '2020-01-29 02:44:19', '2020-01-29 02:44:19', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dispositivo_id` int(11) NOT NULL,
  `etapa_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `dispositivo_id`, `etapa_id`, `created_at`, `updated_at`) VALUES
(2, 2, 2, '2021-02-28 22:51:52', '2021-02-28 22:51:52');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `bairros`
--
ALTER TABLE `bairros`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cidades`
--
ALTER TABLE `cidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `distribuidores`
--
ALTER TABLE `distribuidores`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `etapas`
--
ALTER TABLE `etapas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `matrizes`
--
ALTER TABLE `matrizes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `pontos`
--
ALTER TABLE `pontos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `token_fcm`
--
ALTER TABLE `token_fcm`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_fcm_device_token_unique` (`device_token`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`),
  ADD UNIQUE KEY `users_cpf_unique` (`cpf`);

--
-- Índices para tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `bairros`
--
ALTER TABLE `bairros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cidades`
--
ALTER TABLE `cidades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `distribuidores`
--
ALTER TABLE `distribuidores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `etapas`
--
ALTER TABLE `etapas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `matrizes`
--
ALTER TABLE `matrizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=611;

--
-- AUTO_INCREMENT de tabela `pontos`
--
ALTER TABLE `pontos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `token_fcm`
--
ALTER TABLE `token_fcm`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
