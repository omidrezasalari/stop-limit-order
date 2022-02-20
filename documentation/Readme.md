<div dir="rtl">
  <center><h1>* مستندات پروژه استاپ لیمیت اُردر</h1>  </center>
</div>



<div dir="rtl">
<h3>
    روند اجرای پروژه استاپ لیمیت اُردر به شرح زیر می باشد :
    </h3> 
    <div dir="rtl">
          ابتدا وقتی که کاربر استاپ لیمیت اُردری ثبت می کند قیمت استاپ  (stop)
در کش مربوطه بر اساس نوع سفارش ثبت می شود .
سپس وقتی قیمت لحظه ای درون صف خود قرار گیرد از طریق دستوری (command)
قیمت لحظه دریافت می گردد و بر اساس قوانین مربوط به صف های خرید  و فروش سفارشات استاپ لیمیت
اُردر به ترتیب پردازش می شوند و به چرخه داد و ستد اضافه می گردند.
    </div>
</div>



<div dir="rtl"><strong>&nbsp;جهت دریافت قیمت لحظه ای تستی سرور لاراول را راهندازی کنید و به روت:</strong></div>
```lin
http://127.0.0.1:8000/make-instant-price
```

<div dir="rtl"><b>ریکوئست بزنید</b></div>
<div dir="rtl"><h2><strong>معرفی کامند ها</strong></h2></div>
```shell
1. php artisan get:price
```


<div dir="rtl">
<b>این کامند برای دریافت قیمت  لحظه ای و فراخوانی سفارشات استاپی که آماده اجرا هستند با فرخوانی دستور</b>
<div>



```shell
php artisan check:insert {instantPrice}
```

<div dir="rtl"><b>جهت پردازش فرایند استاپ لیمیت اُردر و فرستاده شدن به صف</b></div>
```shell
2.php artisan check:insert {instantPrice}
```

<div dir="rtl">
<b>    این دستور زمانی اجرا می شود که قیمت لحظه ای را دریافت کن  و ترتیب اجرای آن بصورت:</b> 
</div>

<ul dir="rtl">
<li>دریافت محتوایی که در کَش های سفارش خرید و فروش کاربران ثبت کرده اند</li> 
    <li>مقایسه قیمت های استاپی که کاربران ثبت کرده اند با  قیمت لحظه  بر اساس قوانین خرید و فروش</li>
    <li>ارسال سفارشات واکشی شده  به صف های مربوط به خود </li>
    <li>ریست کردن کش سفارشات خرید و فروش </li>
    <li>ارسال آی دی سفارشات به صف جهت  بروز رسانی اطلاعات در دیتابیس</li>
</ul>



```shell
3.php artisan message:received
```

<div dir="rtl">
    <b>
        این دستور برای دریافت و نمایش پیام هایی که به صف های خرید و فروش ارسال شده است 
    </b>
</div>



```shell
php artisan fake:order
```

<div dir="rtl">
<b>این دستور جهت ایجاد سفارشات تستی جهت انجام فرایند تست می باشد و در هر بار اجرا شدن این دستور 10,000 سفارش به پایگاه داده اضافه می شود</b>
</div>



<h3 style="text-align: right;"><strong>&nbsp; &nbsp;:ترتیب اجرای دستور ها بعد ازارسال درخواست قیمت لحظه ای#&nbsp;</strong></h3>
```shell
php artisan get:price
php artisan message:received
```

<div><b>Postman request documentation<b><div>

```
https://documenter.getpostman.com/view/6454018/TVRd9X39
```



<div dir="rtl">
    <h3>
        <strong>&nbsp; &nbsp;# دستور اجرای (job) هایی که در صف ها وجود دارند.</strong>
    </h3>
</div>

```shell
php artisan queue:work redis --queue=high,default
```

