#! /bin/bash

BASE_DIR=/var/www/webERP;
OUTPUT_DIR=/home/phil/Documents/webERP;
MYSQL_USER=root;
MYSQL_PWD=woofwoof;

cd $BASE_DIR;

xgettext --no-wrap --from-code=utf-8 --language=PHP -o locale/en_GB.utf8/LC_MESSAGES/messages.pot *php includes/*.php includes/*.inc reportwriter/*.php reportwriter/*.inc reportwriter/forms/*.html reportwriter/admin/*.php reportwriter/admin/*.inc reportwriter/admin/forms/*.html api/*.php install/*.php ../webSHOP/*.php ../webSHOP/includes/*.php

msgmerge -U -N --backup=off locale/ar_EG.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/cs_CZ.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/de_DE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/en_US.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/el_GR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/es_ES.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/et_EE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/fa_IR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/fr_CA.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/fr_FR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/hi_IN.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/hr_HR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/hu_HU.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/id_ID.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/it_IT.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/ja_JP.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/lv_LV.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/nl_NL.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/pl_PL.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/pt_BR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/pt_PT.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/ru_RU.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/ro_RO.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/sq_AL.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/sv_SE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/sw_KE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/tr_TR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/zh_CN.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/zh_HK.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/zh_TW.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off locale/vi_VN.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot


msgfmt -o locale/ar_EG.utf8/LC_MESSAGES/messages.mo locale/ar_EG.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/cs_CZ.utf8/LC_MESSAGES/messages.mo locale/cs_CZ.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/de_DE.utf8/LC_MESSAGES/messages.mo locale/de_DE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/el_GR.utf8/LC_MESSAGES/messages.mo locale/el_GR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/en_US.utf8/LC_MESSAGES/messages.mo locale/en_US.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/es_ES.utf8/LC_MESSAGES/messages.mo locale/es_ES.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/et_EE.utf8/LC_MESSAGES/messages.mo locale/et_EE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/fa_IR.utf8/LC_MESSAGES/messages.mo locale/fa_IR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/fr_FR.utf8/LC_MESSAGES/messages.mo  locale/fr_FR.utf8/LC_MESSAGES/messages.po 
msgfmt -o locale/hr_HR.utf8/LC_MESSAGES/messages.mo locale/hr_HR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/hi_IN.utf8/LC_MESSAGES/messages.mo locale/hi_IN.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/hu_HU.utf8/LC_MESSAGES/messages.mo locale/hu_HU.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/id_ID.utf8/LC_MESSAGES/messages.mo locale/id_ID.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/it_IT.utf8/LC_MESSAGES/messages.mo locale/it_IT.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ja_JP.utf8/LC_MESSAGES/messages.mo locale/ja_JP.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/lv_LV.utf8/LC_MESSAGES/messages.mo locale/lv_LV.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/nl_NL.utf8/LC_MESSAGES/messages.mo locale/nl_NL.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/pl_PL.utf8/LC_MESSAGES/messages.mo locale/pl_PL.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/pt_BR.utf8/LC_MESSAGES/messages.mo locale/pt_BR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/pt_PT.utf8/LC_MESSAGES/messages.mo locale/pt_PT.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ro_RO.utf8/LC_MESSAGES/messages.mo locale/ro_RO.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ru_RU.utf8/LC_MESSAGES/messages.mo locale/ru_RU.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/sq_AL.utf8/LC_MESSAGES/messages.mo locale/sq_AL.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/sv_SE.utf8/LC_MESSAGES/messages.mo locale/sv_SE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/sw_KE.utf8/LC_MESSAGES/messages.mo locale/sw_KE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/tr_TR.utf8/LC_MESSAGES/messages.mo locale/tr_TR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/zh_CN.utf8/LC_MESSAGES/messages.mo locale/zh_CN.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/zh_HK.utf8/LC_MESSAGES/messages.mo locale/zh_HK.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/zh_TW.utf8/LC_MESSAGES/messages.mo locale/zh_TW.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/vi_VN.utf8/LC_MESSAGES/messages.mo locale/vi_VN.utf8/LC_MESSAGES/messages.po

mysql -u$MYSQL_USER  -p$MYSQL_PWD < $BASE_DIR/build/TruncateAuditTrail.sql

echo "SET FOREIGN_KEY_CHECKS = 0;" > $BASE_DIR/sql/mysql/country_sql/default.sql

mysqldump -u$MYSQL_USER  -p$MYSQL_PWD  --skip-opt --create-options --skip-set-charset --ignore-table=weberpdemo.mrpsupplies  --ignore-table=weberpdemo.mrpplanedorders --ignore-table=weberpdemo.mrpparameters --ignore-table=weberpdemo.levels --ignore-table=weberpdemo.mrprequirements --ignore-table=weberpdemo.buckets --no-data weberpdemo >> $BASE_DIR/sql/mysql/country_sql/default.sql

mysqldump -u$MYSQL_USER  -p$MYSQL_PWD --skip-opt --skip-set-charset --quick --no-create-info weberpdemo  \
       accountgroups \
       bankaccounts \
       chartmaster \
       companies \
       cogsglpostings \
       currencies \
       holdreasons \
       locations \
       paymentterms \
       reportlinks \
       salesglpostings \
       systypes \
       taxauthorities \
       taxgroups \
       taxauthrates \
       taxcategories \
       taxprovinces \
       www_users \
       edi_orders_segs \
       edi_orders_seg_groups \
       config \
       unitsofmeasure \
       paymentmethods \
       scripts \
       securitygroups \
       securitytokens \
       securityroles \
       accountsection \
       > $BASE_DIR/sql/mysql/country_sql/weberp-base.sql

mysqldump -u$MYSQL_USER  -p$MYSQL_PWD --skip-opt --skip-set-charset --quick --ignore-table=weberpdemo.mrpsupplies  --ignore-table=weberpdemo.mrpplanedorders --ignore-table=weberpdemo.mrpparameters --ignore-table=weberpdemo.levels --ignore-table=weberpdemo.mrprequirements --no-create-info weberpdemo  > $BASE_DIR/sql/mysql/country_sql/weberp-demo_data.sql

rm  $BASE_DIR/sql/mysql/country_sql/demo.sql
echo "CREATE DATABASE IF NOT EXISTS weberpdemo;" > $BASE_DIR/sql/mysql/country_sql/demo.sql
echo "USE weberpdemo;" >> $BASE_DIR/sql/mysql/country_sql/demo.sql

cat $BASE_DIR/sql/mysql/country_sql/default.sql >> $BASE_DIR/sql/mysql/country_sql/demo.sql

cat $BASE_DIR/sql/mysql/country_sql/weberp-base.sql >> $BASE_DIR/sql/mysql/country_sql/default.sql
cat $BASE_DIR/sql/mysql/country_sql/weberp-demo_data.sql >> $BASE_DIR/sql/mysql/country_sql/demo.sql
rm  $BASE_DIR/sql/mysql/country_sql/weberp-demo_data.sql
rm  $BASE_DIR/sql/mysql/country_sql/weberp-base.sql

echo "SET FOREIGN_KEY_CHECKS = 1;" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "UPDATE systypes SET typeno=0;" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "INSERT INTO shippers VALUES (1,'Default Shipper',0);" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "UPDATE config SET confvalue='1' WHERE confname='Default_Shipper';" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "SET FOREIGN_KEY_CHECKS = 1;" >> $BASE_DIR/sql/mysql/country_sql/demo.sql

rm $OUTPUT_DIR/webERP.zip

cd ..

zip -r $OUTPUT_DIR/webERP webERP -x \*.svn* \*/config.php \*build*
