# Datediffer
DateDiff App with pure PHP (without using date-functions)

######Задание:
> Подсчитать разницу между 2-мя входными датами без использования любых PHP-функций, связанных с датой.

*Входящие данные:*
> 2 даты в формате «YYYY-MM-DD» (2015-03-05, например)

*Исходящие данные:*
`stdClass {
int $years, Кол-во лет между датами
int $months, Кол-во месяцев между датами
int $days, Кол-во дней между датами
int $total_days, Общее кол-во дней между двумя датами 
bool $invert true — если дата старта > дата конца
}`

__*БЕЗ использования любых фреймворков, только чистый PHP
Без использования класса DateTime
