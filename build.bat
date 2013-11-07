@echo off
echo This will update composer, run all tests, generate reports in ./build dir, dump component licenses to LICENSES.txt
set /p continue=Do you want to continue ? [y/n] Y

IF %continue% == () (
    set continue=y
    pause
)

IF %continue%==y (
    del LICENSES.txt
    composer update --verbose & composer licenses >> LICENSES.txt
    test
echo Build completed
) else (
    echo Build skipped
)







