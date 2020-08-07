clean:
	rm -rf build

build:
	mkdir build
	ppm --no-intro --compile="src/COASniffle" --directory="build"

install:
	ppm --no-prompt --fix-conflict --install="build/net.intellivoid.coa_sniffle.ppm"