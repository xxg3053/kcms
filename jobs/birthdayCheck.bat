@echo off
echo 生日邮件系统批处理程序
rem 进入kcms系统根目录
d:
cd \documents\ws\kcms
"D:\Program Files\php-5.4.43-Win32-VC9-x86\php.exe" index.php Admin/Birthday/checkBirthday

pause