<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\CodingStyle\Rector\Class_\AddArrayDefaultToArrayPropertyRector;
use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector;
use Rector\CodingStyle\Rector\Switch_\BinarySwitchToIfElseRector;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector;

// @see https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths(
        [
            __DIR__ . '/src',
        ]
    );

    $rectorConfig->sets(
        [
            SetList::CODE_QUALITY,
            SetList::DEAD_CODE,
            SetList::CODING_STYLE,
            SetList::NAMING,  // might be nice to run for some files
            SetList::PRIVATIZATION,
            SetList::TYPE_DECLARATION,
            SetList::TYPE_DECLARATION_STRICT,
            SetList::PSR_4,
            SetList::PHP_52,
            LevelSetList::UP_TO_PHP_80,
        ]
    );
    $rectorConfig->rules(
        [
            InlineConstructorDefaultToPropertyRector::class,
            TypedPropertyRector::class,
            ReturnTypeFromStrictBoolReturnExprRector::class,
            ReturnTypeFromStrictNewArrayRector::class,
        ]
    );

    // ignore for now
    $rectorConfig->skip(
        [
            CountArrayToEmptyArrayComparisonRector::class,  // -> change count array comparison to empty array
            // comparison to improve performance
            SimplifyBoolIdenticalTrueRector::class, // not used to this
            FlipTypeControlToUseExclusiveTypeRector::class, // not used to this
            VarConstantCommentRector::class, // phpcs will say constant does not need type
            PostIncDecToPreIncDecRector::class, // not used to this

            BinarySwitchToIfElseRector::class, // not used to this
            SymplifyQuoteEscapeRector::class, // not used to this
            NewlineBeforeNewAssignSetRector::class, // not used to this

            AddArrayParamDocTypeRector::class, // nice to have - conflicts with phpstan now
            AddArrayReturnDocTypeRector::class, // nice to have - conflicts with phpstan now
            ReturnTypeDeclarationRector::class, // nice to have - conflicts with phpstan now
            ArrayShapeFromConstantArrayReturnRector::class, // nice to have - conflicts with phpstan now
            PropertyTypeDeclarationRector::class, // might be ok

            AddLiteralSeparatorToNumberRector::class, // not used for now.

            AddArrayDefaultToArrayPropertyRector::class, // nice but does work 100% when array is set in constructor

            FinalizeClassesWithoutChildrenRector::class, // nice to have
            ChangeReadOnlyVariableWithDefaultValueToConstantRector::class, // really nice to have - throws an error

            PrivatizeLocalGetterToPropertyRector::class, // not sure about this one, for DI might be ok ~
        ]
    );
};
