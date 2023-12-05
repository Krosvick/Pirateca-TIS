# Pirateca-TIS

pip install numpy, pandas, matplotlib, seaborn, surprise, sklearn

## Donde clonar este repo?
Debido al uso de xampp para el desarrollo de la aplicacion web, se recomienda clonar este repositorio en la carpeta htdocs de xampp. Otro punto a tener en cuenta es el uso de wsl para el desarrollo del algoritmo de recomendacion.

## Como clonar este repo?
Abrir la terminal en el directorio htdocs y ejecutar vscode desde la terminal con el comando:
```bash
code .
```
Una vez abierto vscode, abrir una terminal de wsl en visual studio code y clonar el repositorio con el comando:
```bash
git clone git@github.com:Krosvick/Sufrimiento-TIS.git
```


## Uso y proposito de virtualenv
Virtualenv es una herramienta para crear entornos aislados de Python. Estos entornos pueden tener sus propias instalaciones de paquetes de Python, lo que permite un control más estricto sobre las dependencias y las versiones, y evitar problemas con los requisitos de paquetes para otros proyectos.

## Creacion de un entorno virtual
Para crear un entorno virtual, necesitará instalar virtualenv. Puede instalarlo usando pip:

```bash
pip install virtualenv
```
Una vez que tenga virtualenv instalado, puede crear su entorno virtual. El siguiente comando creará un entorno llamado my_env en el directorio actual y configurará el intérprete de Python para que sea el predeterminado:

```bash
virtualenv my_env
```

## Activacion de un entorno virtual
Una vez que tenga un entorno virtual, deberá activarlo. El siguiente comando activará el entorno virtual:

```bash
source my_env/bin/activate
```

## Instalacion de los paquetes necesarios
Una vez que tenga un entorno virtual, deberá instalar los paquetes necesarios. Para instalar los paquetes necesarios, puede usar el siguiente comando:

```bash
pip install numpy, pandas, matplotlib, seaborn, surprise, sklearn
```

Estos seran instalados en el entorno virtual y no en el sistema operativo.

## Creacion de un archivo requirements.txt
Una vez que haya instalado todos los paquetes necesarios, deberá crear un archivo requirements.txt. Este archivo contendrá una lista de todos los paquetes instalados en el entorno virtual. Para crear un archivo requirements.txt, puede usar el siguiente comando:

```bash
pip freeze > requirements.txt
```
## Instalacion de los paquetes desde el archivo requirements.txt
Una vez que tenga un archivo requirements.txt, puede instalar los paquetes necesarios en otro entorno virtual. Para instalar los paquetes necesarios, puede usar el siguiente comando:

```bash
pip install -r requirements.txt
```

## Desactivacion de un entorno virtual
Una vez que haya terminado de trabajar en su proyecto, puede desactivar el entorno virtual. Esto le permitirá volver a su entorno normal. Para desactivar el entorno virtual, puede usar el siguiente comando:

```bash
deactivate
```

