#!/bin/bash

ROOT_DIR="$(cd "$(dirname $0)" && pwd)"/../../..
ROOT_DIR=`readlink -f $ROOT_DIR`

env=development
event=$1
filenames=$(basename "$2")
all_filenames=`ls $ROOT_DIR/domain/description/`

diff_dir=/tmp/description

controller_diff_dir=$diff_dir/controller
view_diff_dir=$diff_dir/view
view_diff_old_dir=$view_diff_dir/old
view_diff_new_dir=$view_diff_dir/new

echog()
{
    php -r "echo \"\033[32m\"; echo '$1'; echo \"\033[0m\n\";" >&2
}

alias echo_filter='column -t | perl -pe "s/(^migrate|^include)|(^delete|^uninclude|^clean)|(^todo)|(^generate)/\\e[1;34m\$1\\e[0m\\e[1;31m\$2\\e[0m\e[1;30m\$3\\e[0m\e[1;32m\$4\\e[0m/gi"'

if [ "$event" = "INIT" ]
then
    (
    rm -rf $diff_dir
    mkdir -p $controller_diff_dir
    mkdir -p $view_diff_old_dir
    mkdir -p $view_diff_new_dir
    for filename in $all_filenames
    do
        entity_name=${filename%.*}

        controller_file_old=$controller_diff_dir/$entity_name.php.old
        output_controller_file=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-controller-from-description --entity_name=$entity_name --output_file=$controller_file_old`
        echo init $output_controller_file success!

        output_view_files=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-page-from-description --entity_name=$entity_name --output_dir=$view_diff_old_dir`
        for output_view_file in $output_view_files
        do
            echo init $output_view_file success!
        done
    done
    ) | echo_filter
elif [ "${filenames##*.}" = "yml" ]
then

    if [ "$filenames" = ".relationship.yml" ]
    then
        filenames=`ls $ROOT_DIR/domain/description/`
        event="MODIFY"
    fi

    ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php migrate:reset | echo_filter

    for filename in $filenames
    do
        (
        entity_name=${filename%.*}

        if [ "$event" = "CREATE" ] || [ "$event" = "MODIFY" ];then
            echog "watch $filename generate"

            rm -rf $ROOT_DIR/command/migration/tmp/*[0-9]_$entity_name.sql
            echo delete $ROOT_DIR/command/migration/tmp/*_$entity_name.sql success!

            entity_files=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php entity:make-from-description --entity_name=$entity_name`
            for entity_file in $entity_files; do echo generate $entity_file success!; done

            rm -rf $ROOT_DIR/docs/entity/$entity_name.md
            rm -rf $ROOT_DIR/docs/entity/relationship.md
            grep -v "\(entity/$entity_name.md\)" $ROOT_DIR/docs/sidebar.md > /tmp/sidebar.md
            mv /tmp/sidebar.md $ROOT_DIR/docs/sidebar.md
            echo delete $ROOT_DIR/docs/entity/$entity_name.md success!

            docs_entity_files=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php entity:make-docs-from-description --entity_name=$entity_name`
            for docs_entity_file in $docs_entity_files; do echo generate $docs_entity_file success!; done
            menu_name=`cat $ROOT_DIR/domain/description/$entity_name.yml | head -n 2 | tail -n 1 | cut -d ' ' -f 2`
            /bin/sed -i "/实体关联/a\\ \ \-\ \[$menu_name\]\(entity\/$entity_name\.md\)" $ROOT_DIR/docs/sidebar.md
            echo include $ROOT_DIR/docs/entity/$entity_name.md success!

            controller_file=$ROOT_DIR/controller/$entity_name.php
            grep -v "'\/$entity_name\." $ROOT_DIR/public/index.php > /tmp/index.php
            mv /tmp/index.php $ROOT_DIR/public/index.php
            echo uninclude $controller_file success!

            controller_file_old=$controller_diff_dir/$entity_name.php.old
            controller_file_new=$controller_diff_dir/$entity_name.php.new
            controller_file_diff=$ROOT_DIR/controller/$entity_name.diff.php
            output_controller_file=$controller_file
            if [ -r $output_controller_file ]
            then
                output_controller_file=$controller_file_new
                output_controller_file=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-controller-from-description --entity_name=$entity_name --output_file=$output_controller_file`
            else
                output_controller_file=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-controller-from-description --entity_name=$entity_name --output_file=$output_controller_file`
                cp $output_controller_file $controller_file_old
            fi
            echo generate $output_controller_file success!
            if [ -r $controller_file_new ]
            then
                if [ ! -r $controller_file_old ] || test "`diff -u $controller_file $controller_file_old`"
                then
                    cp $controller_file_new $controller_file_old
                    controller_file_diff_str=`diff -u $controller_file_new $controller_file`
                    if test "$controller_file_diff_str"
                    then
                        echo "$controller_file_diff_str" > $controller_file_diff
                        echo generate $controller_file_diff success!
                    fi
                    rm $controller_file_new
                    echo delete $controller_file_new success!
                else
                    cp $controller_file_new $controller_file_old
                    cp $controller_file_new $controller_file
                    echo generate $controller_file success!

                    rm $controller_file_new
                    echo delete $controller_file_new success!
                fi
            fi
            /bin/sed -i "/init\ controller/a\include\ CONTROLLER_DIR\.\'\/$entity_name\.php\'\;" $ROOT_DIR/public/index.php
            echo include $ROOT_DIR/controller/$entity_name.php success!

            view_file_diff_old_dir=$view_diff_old_dir/$entity_name
            view_file_diff_new_dir=$view_diff_new_dir/$entity_name
            view_file_root_dir=$ROOT_DIR/view
            view_file_dir=$view_file_root_dir/$entity_name
            view_file_diff_dir=$view_file_dir.diff
            output_view_file_dir=$view_file_root_dir
            if [ -d $view_file_dir ]
            then
                output_view_file_dir=$view_diff_new_dir
                output_view_files=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-page-from-description --entity_name=$entity_name --output_dir=$output_view_file_dir`
            else
                output_view_files=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-page-from-description --entity_name=$entity_name --output_dir=$output_view_file_dir`
                if [ -d $view_file_diff_old_dir ]
                then
                    cp -r $view_file_dir/* $view_file_diff_old_dir/
                else
                    cp -r $view_file_dir $view_file_diff_old_dir
                fi
            fi
            for output_view_file in $output_view_files; do echo generate $output_view_file success!; done
            if [ -d $view_file_diff_new_dir ]
            then
                if [ ! -d $view_file_diff_old_dir ] || test "`diff -u $view_file_dir $view_file_diff_old_dir`"
                then
                    if [ -d $view_file_diff_old_dir ]
                    then
                        cp -r $view_file_diff_new_dir/* $view_file_diff_old_dir/
                    else
                        cp -r $view_file_diff_new_dir $view_file_diff_old_dir
                    fi

                    for view_file in `ls $view_file_diff_new_dir`
                    do
                        target_view_file=$view_file_dir/$view_file
                        if [ ! -r $target_view_file ]
                        then
                            target_view_file=/dev/null
                        fi

                        view_file_diff_str=`diff -u $view_file_diff_new_dir/$view_file $target_view_file`
                        if test "$view_file_diff_str"
                        then
                            mkdir -p $view_file_diff_dir
                            echo "$view_file_diff_str" > $view_file_diff_dir/$view_file
                            echo generate $view_file_diff_dir/$view_file success!
                        fi
                    done
                    rm -rf $view_file_diff_new_dir
                    echo delete $view_file_diff_new_dir success!
                else
                    if [ -d $view_file_diff_old_dir ]
                    then
                        cp -r $view_file_diff_new_dir/* $view_file_diff_old_dir/
                    else
                        cp -r $view_file_diff_new_dir $view_file_diff_old_dir
                    fi

                    if [ -d $view_file_dir ]
                    then
                        cp -r $view_file_diff_new_dir/* $view_file_dir/
                    else
                        cp -r $view_file_diff_new_dir $view_file_dir
                    fi

                    for view_file in `ls $view_file_dir`
                    do
                        echo generate $view_file_dir/$view_file success!
                    done

                    rm -rf $view_file_diff_new_dir
                    echo delete $view_file_diff_new_dir success!
                fi
            fi

            error_code_file=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-error-code-from-description --entity_name=$entity_name`
            echo generate $error_code_file success!

            error_code_doc_file=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-error-code-docs-from-description --entity_name=$entity_name`
            echo generate $error_code_doc_file success!

            rm -rf $ROOT_DIR/docs/page/$entity_name.md
            grep -v "\(page/$entity_name.md\)" $ROOT_DIR/docs/sidebar.md > /tmp/sidebar.md
            mv /tmp/sidebar.md $ROOT_DIR/docs/sidebar.md
            rm -rf $ROOT_DIR/docs/api/$entity_name.md
            grep -v "\(api/$entity_name.md\)" $ROOT_DIR/docs/sidebar.md > /tmp/sidebar.md
            mv /tmp/sidebar.md $ROOT_DIR/docs/sidebar.md
            grep -v "\($entity_name\)" $ROOT_DIR/docs/coverpage.md > /tmp/coverpage.md
            mv /tmp/coverpage.md $ROOT_DIR/docs/coverpage.md
            echo delete $ROOT_DIR/docs/page/$entity_name.md success!
            echo delete $ROOT_DIR/docs/api/$entity_name.md success!

            docs_api_files=`ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php crud:make-docs-from-description --entity_name=$entity_name`
            for docs_api_file in $docs_api_files; do echo generate $docs_api_file success!; done
            menu_name=`cat $ROOT_DIR/domain/description/$entity_name.yml | head -n 2 | tail -n 1 | cut -d ' ' -f 2`
            /bin/sed -i "/页面文档/a\\ \ \-\ \[$menu_name\]\(page\/$entity_name\.md\)" $ROOT_DIR/docs/sidebar.md
            /bin/sed -i "/接口文档/a\\ \ \-\ \[$menu_name\]\(api\/$entity_name\.md\)" $ROOT_DIR/docs/sidebar.md
            /bin/sed -i "/系统的能力/a\\-\ $menu_name管理\ \($entity_name\)" $ROOT_DIR/docs/coverpage.md
            echo include $ROOT_DIR/docs/page/$entity_name.md success!
            echo include $ROOT_DIR/docs/api/$entity_name.md success!

            grep -v "'$entity_name'" $ROOT_DIR/controller/index.php > /tmp/controller_index.php
            mv /tmp/controller_index.php $ROOT_DIR/controller/index.php
            list_url=`cat $ROOT_DIR/controller/$entity_name.php | head -n 3 | tail -n 1 | cut -d "'" -f 2`
            menu_name=`cat $ROOT_DIR/domain/description/$entity_name.yml | head -n 2 | tail -n 1 | cut -d ' ' -f 2`
            /bin/sed -i "/url_infos/a\\ \ \ \ \ \ \ \ \ \ \ \ [\ 'name'\ =>\ \'$menu_name管理\',\ \'key\'\ =>\ \'$entity_name\',\ \'href\'\ =>\ \'$list_url\',\ ]," $ROOT_DIR/controller/index.php
        fi

        if [ "$event" = "DELETE" ];then

            echog "watch $filename delete"

            rm -rf $ROOT_DIR/command/migration/tmp/*[0-9]_$entity_name.sql
            echo delete $ROOT_DIR/command/migration/tmp/*_$entity_name.sql success!

            rm -rf $ROOT_DIR/domain/dao/$entity_name.php
            echo delete $ROOT_DIR/domain/dao/$entity_name.php success!
            rm -rf $ROOT_DIR/domain/entity/$entity_name.php
            echo delete $ROOT_DIR/domain/entity/$entity_name.php success!

            rm -rf $ROOT_DIR/docs/entity/$entity_name.md
            rm -rf $ROOT_DIR/docs/entity/relationship.md
            grep -v "\(entity/$entity_name.md\)" $ROOT_DIR/docs/sidebar.md > /tmp/sidebar.md
            mv /tmp/sidebar.md $ROOT_DIR/docs/sidebar.md
            echo delete $ROOT_DIR/docs/entity/$entity_name.md success!

            rm -rf $ROOT_DIR/controller/$entity_name.php
            grep -v "'\/$entity_name\." $ROOT_DIR/public/index.php > /tmp/index.php
            mv /tmp/index.php $ROOT_DIR/public/index.php
            echo delete $ROOT_DIR/controller/$entity_name.php success!

            rm -rf $ROOT_DIR/view/$entity_name
            rm -rf $ROOT_DIR/view/$entity_name.diff
            echo delete $ROOT_DIR/view/$entity_name/*.php success!

            sed -i "/\/\*\ generated\ ${entity_name}\ start\ \*\//,/\/\*\ generated\ ${entity_name}\ end\ \*\//d" $ROOT_DIR/config/error_code.php
            echo clean $ROOT_DIR/config/error_code.php success!

            sed -i "/\[\^\_\^\]:\ ${entity_name}_start/,/\[\^\_\^\]:\ ${entity_name}_end/d" $ROOT_DIR/docs/error_code.md
            echo clean $ROOT_DIR/docs/error_code.md success!

            rm -rf $ROOT_DIR/docs/page/$entity_name.md
            grep -v "\(page/$entity_name.md\)" $ROOT_DIR/docs/sidebar.md > /tmp/sidebar.md
            mv /tmp/sidebar.md $ROOT_DIR/docs/sidebar.md
            rm -rf $ROOT_DIR/docs/api/$entity_name.md
            grep -v "\(api/$entity_name.md\)" $ROOT_DIR/docs/sidebar.md > /tmp/sidebar.md
            mv /tmp/sidebar.md $ROOT_DIR/docs/sidebar.md
            grep -v "\($entity_name\)" $ROOT_DIR/docs/coverpage.md > /tmp/coverpage.md
            mv /tmp/coverpage.md $ROOT_DIR/docs/coverpage.md
            echo delete $ROOT_DIR/docs/page/$entity_name.md success!
            echo delete $ROOT_DIR/docs/api/$entity_name.md success!

            grep -v "'$entity_name'" $ROOT_DIR/controller/index.php > /tmp/controller_index.php
            mv /tmp/controller_index.php $ROOT_DIR/controller/index.php
        fi

        ) | echo_filter
    done

    /bin/bash $ROOT_DIR/project/tool/classmap.sh $ROOT_DIR/domain | echo_filter
    ENV=$env /usr/bin/php $ROOT_DIR/public/cli.php migrate -tmp_files | echo_filter
fi
