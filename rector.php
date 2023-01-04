<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector;
use Rector\Php53\Rector\Variable\ReplaceHttpServerVarsByServerRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Symfony\Rector\ClassMethod\ConsoleExecuteReturnIntRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use WeCreateSolutions\Rector\WafConfigs;
use WeCreateSolutions\Rector\WcsBase;

// @see https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md

return static function (RectorConfig $rectorConfig): void {

    $projectRoot = WcsBase::getProjectRoot();

    $rectorConfig->paths(
        [
            $projectRoot . '/src',
        ]
    );

    // @see https://github.com/rectorphp/rector/blob/main/docs/how_to_persist_cache_between_ci_runs.md
    $rectorConfig->cacheDirectory($projectRoot . '/var/tmp/rector');

    // @see https://github.com/rectorphp/rector/blob/main/docs/how_to_troubleshoot_parallel_issues.md
    $rectorConfig->parallel(4800);

    // @see https://github.com/rectorphp/rector/blob/main/docs/auto_import_names.md
    $rectorConfig->importNames();

//    $rectorConfig->sets(
//        [
//            // region base
//            LevelSetList::UP_TO_PHP_74,
//            SetList::CODE_QUALITY,
//            SetList::CODING_STYLE,
//            SetList::DEAD_CODE,
//            SetList::PSR_4,
//            // SetList::NAMING,  // might be nice to run for some files
//            // SetList::PRIVATIZATION,
//            SetList::TYPE_DECLARATION,
//            // endregion
//
//            // region third party
//            // region SensiolabsSetList
//            SensiolabsSetList::FRAMEWORK_EXTRA_40,
//            SensiolabsSetList::FRAMEWORK_EXTRA_50,
//            SensiolabsSetList::FRAMEWORK_EXTRA_61,
//            SensiolabsSetList::ANNOTATIONS_TO_ATTRIBUTES,
//            // endregion
//
//            // region SymfonySetList
//            SymfonyLevelSetList::UP_TO_SYMFONY_54,
//            SymfonySetList::SYMFONY_CODE_QUALITY,
//            SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
//            // endregion
//
//            // region doctrine
//            DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
//            DoctrineSetList::DOCTRINE_CODE_QUALITY,
//            DoctrineSetList::DOCTRINE_DBAL_30,
//            // endregion
//
//            // region twig
//            TwigLevelSetList::UP_TO_TWIG_240,
//            // endregion
//
//            // endregion
//        ]
//    );

    $rectorConfig->rules(
        [
            //            TypedPropertyRector::class, // UNSAFE
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AnnotationToAttributeRector::class,
        [
        ]
    );

    // @see https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md
    $rectorConfig->skip(
        array_merge(
            WafConfigs::SKIPS,
            [
                FuncGetArgsToVariadicParamRector::class    => [],

                // region slow rules
                TypedPropertyFromAssignsRector::class,
                RemoveEmptyMethodCallRector::class,
                ParamTypeByParentCallTypeRector::class     => [],
                ReplaceHttpServerVarsByServerRector::class => [],
                // endregion

                // region to review with team
                CombineIfRector::class                     => [],
                // endregion

                // region false-positive
                ConsoleExecuteReturnIntRector::class       => [
                ],
                // endregion

                Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector::class, // https://blog.jetbrains.com/phpstorm/2022/06/phpstorm-2022-2-eap-5/#Anonymous_var_in_return_statements
            ]
        )
    );
};
