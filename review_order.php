<?php

if (!defined("review_order")) {

  echo "<script> window.open('checkout.php','_self'); </script>";
}

$select_payment_settings = "select * from payment_settings";

$run_payment_settings = mysqli_query($con, $select_payment_settings);

$row_payment_settings = mysqli_fetch_array($run_payment_settings);

$enable_paypal = $row_payment_settings['enable_paypal'];

$paypal_email = $row_payment_settings['paypal_email'];

$paypal_currency_code = $row_payment_settings['paypal_currency_code'];

$paypal_sandbox = $row_payment_settings['paypal_sandbox'];

$enable_stripe = $row_payment_settings['enable_stripe'];

$enable_umspay = $row_payment_settings['enable_umspay'];

$enable_pesapal = $row_payment_settings['enable_pesapal'];

if ($paypal_sandbox == "on") {

  $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} elseif ($paypal_sandbox == "off") {

  $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

?>

<div class="row"><!--- row Starts -->

  <?php

  $ip_add = getRealUserIp();

  $select_cart = "select * from cart where ip_add='$ip_add'";

  $run_cart = mysqli_query($con, $select_cart);

  $count_cart = mysqli_num_rows($run_cart);

  if ($count_cart == 0) {

  ?>

    <div class="col-md-12"><!-- col-md-12 Starts -->

      <div class="box text-center"><!-- box Starts -->

        <p class="lead"> Checkout Is Not Available Your Cart Is Currently Empty. </p>

        <a href="shop.php" class="btn btn-primary btn-lg"> Return To Shop </a>

      </div><!-- box Ends -->

    </div><!-- col-md-12 Ends -->

  <?php } else { ?>

    <div class="col-md-8"><!-- col-md-8 Starts -->

      <div class="box"><!-- box Starts -->

        <p class="lead">Please Feel Free To Check Your Billing Details And Shipping Details.</p>

        <?php

        $customer_email = $_SESSION['customer_email'];

        $select_customer = "select * from customers where customer_email='$customer_email'";

        $run_customer = mysqli_query($con, $select_customer);

        $row_customer = mysqli_fetch_array($run_customer);

        $customer_id = $row_customer['customer_id'];

        $customer_contact = $row_customer['customer_contact'];

        $get_customers_addresses = "select * from customers_addresses where customer_id='$customer_id'";

        $run_customers_addresses = mysqli_query($con, $get_customers_addresses);

        $row_addresses = mysqli_fetch_array($run_customers_addresses);

        $billing_first_name = $row_addresses["billing_first_name"] ?? '';

        $billing_last_name = $row_addresses["billing_last_name"]  ?? '';

        $billing_country = $row_addresses["billing_country"]  ?? '';

        $billing_address_1 = $row_addresses["billing_address_1"]  ?? '';

        $billing_address_2 = $row_addresses["billing_address_2"]  ?? '';

        $billing_state = $row_addresses["billing_state"]  ?? '';

        $billing_city = $row_addresses["billing_city"]  ?? '';

        $billing_postcode = $row_addresses["billing_postcode"]  ?? '';

        $shipping_first_name = $row_addresses["shipping_first_name"]  ?? '';

        $shipping_last_name = $row_addresses["shipping_last_name"]  ?? '';

        $shipping_country = $row_addresses["shipping_country"]  ?? '';

        $shipping_address_1 = $row_addresses["shipping_address_1"]  ?? '';

        $shipping_address_2 = $row_addresses["shipping_address_2"]  ?? '';

        $shipping_state = $row_addresses["shipping_state"]  ?? '';

        $shipping_city = $row_addresses["shipping_city"]  ?? '';

        $shipping_postcode = $row_addresses["shipping_postcode"]  ?? '';

        $physical_products = array();

        $ip_add = getRealUserIp();

        $select_cart = "select * from cart where ip_add='$ip_add'";

        $run_cart = mysqli_query($con, $select_cart);

        while ($row_cart = mysqli_fetch_array($run_cart)) {

          $product_id = $row_cart['p_id'];

          $product_type = $row_cart['product_type'];

          $select_product = "select * from products where product_id='$product_id'";

          $run_product = mysqli_query($con, $select_product);

          $row_product = mysqli_fetch_array($run_product);

          $vendor_id = $row_product['vendor_id'];

          if ($product_type == "physical_product") {

            if (!isset($physical_products[$vendor_id])) {

              $physical_products[$vendor_id] = array();
            }

            array_push($physical_products[$vendor_id], $product_id);
          }
        }

        ?>

        <form method="post" enctype="multipart/form-data" id="shipping-billing-details-form"><!-- shipping-billing-details-form Starts -->

          <h2> Billing Details </h2>

          <div class="row"><!-- row Starts -->

            <div class="col-sm-6">

              <div class="form-group"><!-- form-group Starts -->

                <label> First name : </label>

                <input type="text" name="billing_first_name" class="form-control" required value="<?php echo $billing_first_name; ?>">

              </div><!-- form-group Ends -->

            </div>

            <div class="col-sm-6">

              <div class="form-group"><!-- form-group Starts -->

                <label> Last name : </label>

                <input type="text" name="billing_last_name" class="form-control" required value="<?php echo $billing_last_name; ?>">

              </div><!-- form-group Ends -->

            </div>

          </div><!-- row Ends -->

          <div class="form-group"><!-- form-group Starts -->

            <label> Country : </label>

            <select name="billing_country" class="form-control" required>

              <option value=""> Select A Country </option>

              <?php

              $get_countries = "select * from countries";

              $run_countries = mysqli_query($con, $get_countries);

              while ($row_countries = mysqli_fetch_array($run_countries)) {

                $country_id = $row_countries['country_id'];

                $country_name = $row_countries['country_name'];

              ?>

                <option value="<?php echo $country_id; ?>" <?php

                                                            if ($billing_country == $country_id) {
                                                              echo "selected";
                                                            }

                                                            ?>>

                  <?php echo $country_name; ?>

                </option>

              <?php } ?>

            </select>

          </div><!-- form-group Ends -->

          <div class="form-group"><!-- form-group Starts -->

            <label> Address 1 : </label>

            <input type="text" name="billing_address_1" class="form-control" required value="<?php echo $billing_address_1; ?>">

          </div><!-- form-group Ends -->

          <div class="form-group"><!-- form-group Starts -->

            <label> Address 2 : </label>

            <input type="text" name="billing_address_2" class="form-control" required value="<?php echo $billing_address_2; ?>">

          </div><!-- form-group Ends -->

          <div class="row"><!-- row Starts -->

            <div class="col-sm-6">

              <div class="form-group"><!-- form-group Starts -->

                <label> State / County : </label>

                <input type="text" name="billing_state" class="form-control" required value="<?php echo $billing_state; ?>">

              </div><!-- form-group Ends -->

            </div>

            <div class="col-sm-6">

              <div class="form-group"><!-- form-group Starts -->

                <label> Town / City : </label>

                <input type="text" name="billing_city" class="form-control" required value="<?php echo $billing_city; ?>">

              </div><!-- form-group Ends -->

            </div>


          </div><!-- row Ends -->

          <div class="form-group"><!-- form-group Starts -->

            <label> Postcode / ZIP : </label>

            <input type="text" name="billing_postcode" class="form-control" required value="<?php echo $billing_postcode; ?>">

          </div><!-- form-group Ends -->

          <?php if (count($physical_products) > 0) { ?>

            <hr>

            <div class="form-group"><!-- form-group Starts -->

              <h4>Is Shipping Details Are The Same?</h4>

              <?php

              if (!isset($_SESSION["is_shipping_address"])) {

                $_SESSION["is_shipping_address"] = "yes";
              }

              ?>

              <?php if (@$_SESSION["is_shipping_address"] == "yes") { ?>

                <input type="radio" name="is_shipping_address" value="yes" checked>

                <label>Yes</label>

                <input type="radio" name="is_shipping_address" value="no">

                <label>No</label>

              <?php } elseif (@$_SESSION["is_shipping_address"] == "no") { ?>

                <input type="radio" name="is_shipping_address" value="yes">

                <label>Yes</label>

                <input type="radio" name="is_shipping_address" value="no" checked>

                <label>No</label>

              <?php } ?>

            </div><!-- form-group Ends -->

            <div id="shipping-details-form-div"><!-- shipping-details-form-div Starts -->

              <h2> Shipping Details </h2>

              <div class="row"><!-- row Starts -->

                <div class="col-sm-6">

                  <div class="form-group"><!-- form-group Starts -->

                    <label> First name : </label>

                    <input type="text" name="shipping_first_name" class="form-control" required value="<?php echo $shipping_first_name; ?>">

                  </div><!-- form-group Ends -->

                </div>

                <div class="col-sm-6">

                  <div class="form-group"><!-- form-group Starts -->

                    <label> Last name : </label>

                    <input type="text" name="shipping_last_name" class="form-control" value="<?php echo $shipping_last_name; ?>" required>

                  </div><!-- form-group Ends -->

                </div>

              </div><!-- row Ends -->

              <div class="form-group"><!-- form-group Starts -->

                <label> Country : </label>

                <select name="shipping_country" class="form-control" required>

                  <option value=""> Select A Country </option>

                  <?php

                  $get_countries = "select * from countries";

                  $run_countries = mysqli_query($con, $get_countries);

                  while ($row_countries = mysqli_fetch_array($run_countries)) {

                    $country_id = $row_countries['country_id'];

                    $country_name = $row_countries['country_name'];

                  ?>

                    <option value="<?php echo $country_id; ?>" <?php

                                                                if ($shipping_country == $country_id) {
                                                                  echo "selected";
                                                                }

                                                                ?>>

                      <?php echo $country_name; ?>

                    </option>

                  <?php } ?>

                </select>

              </div><!-- form-group Ends -->

              <div class="form-group"><!-- form-group Starts -->

                <label> Address 1 : </label>

                <input type="text" name="shipping_address_1" class="form-control" required value="<?php echo $shipping_address_1; ?>">

              </div><!-- form-group Ends -->

              <div class="form-group"><!-- form-group Starts -->

                <label> Address 2 : </label>

                <input type="text" name="shipping_address_2" class="form-control" required value="<?php echo $shipping_address_2; ?>">

              </div><!-- form-group Ends -->

              <div class="row"><!-- row Starts -->

                <div class="col-sm-6">

                  <div class="form-group"><!-- form-group Starts -->

                    <label> State / County : </label>

                    <input type="text" name="shipping_state" class="form-control" required value="<?php echo $shipping_state; ?>">

                  </div><!-- form-group Ends -->

                </div>

                <div class="col-sm-6">

                  <div class="form-group"><!-- form-group Starts -->

                    <label> Town / City : </label>

                    <input type="text" name="shipping_city" class="form-control" required value="<?php echo $shipping_city; ?>">

                  </div><!-- form-group Ends -->

                </div>

              </div><!-- row Ends -->

              <div class="form-group"><!-- form-group Starts -->

                <label> Postcode / ZIP : </label>

                <input type="text" name="shipping_postcode" class="form-control" required value="<?php echo $shipping_postcode; ?>">

              </div><!-- form-group Ends -->

            </div><!-- shipping-details-form-div Ends -->

          <?php } ?>

          <div class="form-group"><!-- form-group Starts -->

            <label> Order Notes : </label>

            <textarea name="order_note" rows="3" placeholder="Notes about your order, e.g. special notes for delivery." class="form-control"></textarea>

          </div><!-- form-group Ends -->

          <input type="submit" name="submit" id="shipping-billing-form-submit" style="display:none;">

        </form><!-- shipping-billing-details-form Ends -->

      </div><!-- box Ends -->

    </div><!-- col-md-8 Ends -->

    <div class="col-md-4"><!-- col-md-4 Starts -->

      <div class="box" id="order-summary"><!-- box Starts -->

        <div class="box-header"><!-- box-header Starts -->

          <h3>Order Summary</h3>

        </div><!-- box-header Ends -->

        <table class="table">

          <thead>

            <tr>
              <th class="text-muted lead"><strong>Product:</strong></th>
              <th class="text-muted lead"><strong>Total:</strong></th>

            </tr>

          </thead>

          <tbody id="checkout-tbody-reload">

            <?php

            $total = 0;

            $total_weight = array();

            $vendors_ids = array();

            $select_cart = "select * from cart where ip_add='$ip_add'";

            $run_cart = mysqli_query($con, $select_cart);

            while ($row_cart = mysqli_fetch_array($run_cart)) {

              $cart_id = $row_cart['cart_id'];

              $product_id = $row_cart['p_id'];

              $product_qty = $row_cart['qty'];

              $product_price = $row_cart['p_price'];

              $product_weight = $row_cart['product_weight'];

              $get_products = "select * from products where product_id='$product_id'";

              $run_products = mysqli_query($con, $get_products);

              $row_products = mysqli_fetch_array($run_products);

              $vendor_id = $row_products['vendor_id'];

              $product_title = $row_products['product_title'];

              if (!empty($vendor_id)) {

                if (!in_array($vendor_id, $vendors_ids)) {

                  array_push($vendors_ids, $vendor_id);
                }
              }

              $sub_total = $product_price * $product_qty;

              $total += $sub_total;

              $sub_total_weight = $product_weight * $product_qty;

              @$total_weight[$vendor_id] += $sub_total_weight;

              if (strpos($vendor_id, "admin_") !== false) {

                $admin_id = trim($vendor_id, "admin_");

                $get_admin = "select * from admins where admin_id='$admin_id'";

                $run_admin = mysqli_query($con, $get_admin);

                $row_admin = mysqli_fetch_array($run_admin);

                $vendor_name = $row_admin['admin_name'];
              } else {

                $get_customer = "select * from customers where customer_id='$vendor_id'";

                $run_customer = mysqli_query($con, $get_customer);

                $row_customer = mysqli_fetch_array($run_customer);

                $vendor_name = $row_customer['customer_name'] ?? '';
              }

            ?>

              <tr>

                <td>

                  <a href="#" class="bold"> <?php echo $product_title; ?> </a>

                  <i class="fa fa-times" title="Product Qty"></i> <?php echo $product_qty; ?>

                  <p class="cart-product-meta">

                    <?php

                    $cart_meta = "";

                    $select_cart_meta = "select * from cart_meta where ip_add='$ip_add' and cart_id='$cart_id' and product_id='$product_id' and not meta_key='variation_id'";

                    $run_cart_meta = mysqli_query($con, $select_cart_meta);

                    while ($row_cart_meta = mysqli_fetch_array($run_cart_meta)) {

                      $meta_key = ucwords($row_cart_meta["meta_key"]);

                      $meta_value = $row_cart_meta["meta_value"];

                      $cart_meta .= "$meta_key: <span class='text-muted'> $meta_value </span>, ";
                    }

                    echo rtrim($cart_meta, ", ");

                    ?>

                  </p>

                  <p style="margin-top:6px; margin-bottom:-1px;">

                    <strong> Vendor : </strong> <?php echo $vendor_name; ?>

                  </p>

                </td>

                <th>Ksh <?php echo $sub_total; ?></th>

              </tr>

            <?php } ?>

            <tr>

              <th class="text-muted">Subtotal:</th>

              <th>Ksh <?php echo $total; ?></th>

            </tr>

            <?php if (count($physical_products) > 0) { ?>

              <tr>

                <th colspan="2">

                  <p class="shipping-header text-muted"> <i class="fa fa-truck"></i> Shipping: </p>

                  <ul class="shipping-ul-list list-unstyled">

                    <?php

                    foreach ($vendors_ids as $vendor_id) {

                      if (isset($physical_products[$vendor_id])) {

                        $shipping_zone_id = "";

                        if (strpos($vendor_id, "admin_") !== false) {

                          $admin_id = trim($vendor_id, "admin_");

                          $get_admin = "select * from admins where admin_id='$admin_id'";

                          $run_admin = mysqli_query($con, $get_admin);

                          $row_admin = mysqli_fetch_array($run_admin);

                          $vendor_name = $row_admin['admin_name'];
                        } else {

                          $get_customer = "select * from customers where customer_id='$vendor_id'";

                          $run_customer = mysqli_query($con, $get_customer);

                          $row_customer = mysqli_fetch_array($run_customer);

                          $vendor_name = $row_customer['customer_name'] ?? '';
                        }

                    ?>

                        <div class="shipping-vendor-header"> <?php echo $vendor_name; ?> Shipping: </div>

                        <?php

                        if (@$_SESSION["is_shipping_address"] == "yes") {

                          if (empty($billing_country) and empty($billing_postcode)) {

                            echo "

<li> 

<p> There are no shipping methods available. Please double check your address, or contact us if you need any help. </p> 

</li>

";
                          }

                          $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                          $run_zones = mysqli_query($con, $select_zones);

                          while ($row_zones = mysqli_fetch_array($run_zones)) {

                            $zone_id = $row_zones['zone_id'];

                            $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$billing_country' and location_type='country')";

                            $run_zones_locations = mysqli_query($con, $select_zone_locations);

                            $count_zones_locations = mysqli_num_rows($run_zones_locations);

                            if ($count_zones_locations != "0") {

                              $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                              $zone_id = $row_zones_locations["zone_id"];

                              $select_zone_shipping = "select * from shipping where shipping_zone='$zone_id'";

                              $run_zone_shipping = mysqli_query($con, $select_zone_shipping);

                              $count_zone_shipping = mysqli_num_rows($run_zone_shipping);

                              if ($count_zone_shipping != "0") {

                                $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                                $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                                $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                                if ($count_zones_postcodes != "0") {

                                  while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                                    $location_code = $row_zones_postcodes["location_code"];

                                    if ($location_code == $billing_postcode) {

                                      $shipping_zone_id = $zone_id;
                                    }
                                  }
                                } else {

                                  $shipping_zone_id = $zone_id;
                                }
                              }
                            }
                          }
                        } elseif (@$_SESSION["is_shipping_address"] == "no") {

                          if (empty($shipping_country) and empty($shipping_postcode)) {

                            echo "

<li> 

<p> There are no shipping methods available. Please double check your address, or contact us if you need any help. </p> 

</li>

";
                          }

                          $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                          $run_zones = mysqli_query($con, $select_zones);

                          while ($row_zones = mysqli_fetch_array($run_zones)) {

                            $zone_id = $row_zones['zone_id'];

                            $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$shipping_country' and location_type='country')";

                            $run_zones_locations = mysqli_query($con, $select_zone_locations);

                            $count_zones_locations = mysqli_num_rows($run_zones_locations);

                            if ($count_zones_locations != "0") {

                              $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                              $zone_id = $row_zones_locations["zone_id"];

                              $select_zone_shipping = "select * from shipping where shipping_zone='$zone_id'";

                              $run_zone_shipping = mysqli_query($con, $select_zone_shipping);

                              $count_zone_shipping = mysqli_num_rows($run_zone_shipping);

                              if ($count_zone_shipping != "0") {

                                $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                                $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                                $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                                if ($count_zones_postcodes != "0") {

                                  while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                                    $location_code = $row_zones_postcodes["location_code"];

                                    if ($location_code == $shipping_postcode) {

                                      $shipping_zone_id = $zone_id;
                                    }
                                  }
                                } else {

                                  $shipping_zone_id = $zone_id;
                                }
                              }
                            }
                          }
                        } else {

                          if (empty($billing_country) and empty($billing_postcode)) {

                            echo "

<li> 

<p> There are no shipping methods available. Please double check your address, or contact us if you need any help. </p> 

</li>

";
                          }

                          $select_zones = "select * from zones where vendor_id='$vendor_id' order by zone_order DESC";

                          $run_zones = mysqli_query($con, $select_zones);

                          while ($row_zones = mysqli_fetch_array($run_zones)) {

                            $zone_id = $row_zones['zone_id'];

                            $select_zone_locations = "select DISTINCT zone_id from zones_locations where zone_id='$zone_id' and (location_code='$billing_country' and location_type='country')";

                            $run_zones_locations = mysqli_query($con, $select_zone_locations);

                            $count_zones_locations = mysqli_num_rows($run_zones_locations);

                            if ($count_zones_locations != "0") {

                              $row_zones_locations = mysqli_fetch_array($run_zones_locations);

                              $zone_id = $row_zones_locations["zone_id"];

                              $select_zone_postcodes = "select * from zones_locations where zone_id='$zone_id' and location_type='postcode'";

                              $run_zones_postcodes = mysqli_query($con, $select_zone_postcodes);

                              $count_zones_postcodes = mysqli_num_rows($run_zones_postcodes);

                              if ($count_zones_postcodes != "0") {

                                while ($row_zones_postcodes = mysqli_fetch_array($run_zones_postcodes)) {

                                  $location_code = $row_zones_postcodes["location_code"];

                                  if ($location_code == $billing_postcode) {

                                    $shipping_zone_id = $zone_id;
                                  }
                                }
                              } else {

                                $shipping_zone_id = $zone_id;
                              }
                            }
                          }
                        }

                        $shipping_weight = $total_weight[$vendor_id];

                        if (!empty($shipping_zone_id)) {

                          $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_zone = '$shipping_zone_id'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'yes'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";

                          $run_shipping = mysqli_query($con, $select_shipping);

                          $i = 0;

                          while ($row_shipping = mysqli_fetch_array($run_shipping)) {

                            $i++;

                            $type_id = $row_shipping["type_id"];

                            $type_name = $row_shipping["type_name"];

                            $type_default = $row_shipping["type_default"];

                            $shipping_cost = $row_shipping["shipping_cost"];

                            if (!empty($shipping_cost)) {

                        ?>

                              <li>

                                <input type="radio" name="[<?php echo $vendor_id; ?>][shipping_type]" value="<?php echo $type_id; ?>" class="shipping_type" data-shipping_cost="<?php echo $shipping_cost; ?>" <?php

                                                                                                                                                                                                                if ($type_default == "yes") {

                                                                                                                                                                                                                  $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                                                                                                                                                                                  $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                                                                                                                                                                                  echo "checked";
                                                                                                                                                                                                                } elseif ($i == 1) {

                                                                                                                                                                                                                  $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                                                                                                                                                                                  $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                                                                                                                                                                                  echo "checked";
                                                                                                                                                                                                                }

                                                                                                                                                                                                                ?>>

                                <span class="shipping-type-name">

                                  <?php echo $type_name; ?>: <span class="text-muted"> $<?php echo $shipping_cost; ?> </span>

                                </span>

                              </li>


                              <?php

                            }
                          }
                        } else {

                          if (!empty($billing_country) or !empty($shipping_country)) {

                            if (@$_SESSION["is_shipping_address"] == "yes") {

                              $select_country_shipping = "select * from shipping where shipping_country='$billing_country'";
                            } elseif (@$_SESSION["is_shipping_address"] == "no") {

                              $select_country_shipping = "select * from shipping where shipping_country='$shipping_country'";
                            } else {

                              $select_country_shipping = "select * from shipping where shipping_country='$billing_country'";
                            }

                            $run_country_shipping = mysqli_query($con, $select_country_shipping);

                            $count_country_shipping = mysqli_num_rows($run_country_shipping);

                            if ($count_country_shipping == "0") {

                              echo "

<li> 

<p> There are no shipping methods matched/available for your address, or contact us if you need any help. </p> 

</li>

";
                            } else {

                              if (@$_SESSION["is_shipping_address"] == "yes") {

                                $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";
                              } elseif (@$_SESSION["is_shipping_address"] == "no") {

                                $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$shipping_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";
                              } else {

                                $select_shipping = "
SELECT *,
IF (
$shipping_weight > (
SELECT MAX(shipping_weight)
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
ORDER BY shipping_weight DESC
LIMIT 0, 1
),
(
SELECT shipping_cost
FROM shipping
WHERE shipping_type = type_id
AND shipping_country = '$billing_country'
AND shipping_weight >= '$shipping_weight'
ORDER BY shipping_weight ASC
LIMIT 0, 1
)
) AS shipping_cost
FROM shipping_type
WHERE type_local = 'no'
and vendor_id='$vendor_id'
ORDER BY type_order ASC
";
                              }

                              $run_shipping = mysqli_query($con, $select_shipping);

                              $i = 0;

                              while ($row_shipping = mysqli_fetch_array($run_shipping)) {

                                $i++;

                                $type_id = $row_shipping["type_id"];

                                $type_name = $row_shipping["type_name"];

                                $type_default = $row_shipping["type_default"];

                                $shipping_cost = $row_shipping["shipping_cost"];

                                if (!empty($shipping_cost)) {

                              ?>

                                  <li>

                                    <input type="radio" name="[<?php echo $vendor_id; ?>][shipping_type]" value="<?php echo $type_id; ?>" class="shipping_type" data-shipping_cost="<?php echo $shipping_cost; ?>" <?php

                                                                                                                                                                                                                    if ($type_default == "yes") {

                                                                                                                                                                                                                      $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                                                                                                                                                                                      $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                                                                                                                                                                                      echo "checked";
                                                                                                                                                                                                                    } elseif ($i == 1) {

                                                                                                                                                                                                                      $_SESSION["shipping_type_$vendor_id"] = $type_id;

                                                                                                                                                                                                                      $_SESSION["shipping_cost_$vendor_id"] = $shipping_cost;

                                                                                                                                                                                                                      echo "checked";
                                                                                                                                                                                                                    }

                                                                                                                                                                                                                    ?>>

                                    <span class="shipping-type-name">

                                      <?php echo $type_name; ?>: <span class="text-muted"> Ksh <?php echo $shipping_cost; ?> </span>

                                    </span>

                                  </li>

                    <?php

                                }
                              }
                            }
                          }
                        }
                      }
                    }

                    $shipping_types = array();

                    $total_shipping_cost = 0;

                    if (count($physical_products) > 0) {

                      foreach ($vendors_ids as $vendor_id) {

                        if (isset($physical_products[$vendor_id])) {

                          if (isset($_SESSION["shipping_type_$vendor_id"]) and isset($_SESSION["shipping_cost_$vendor_id"])) {

                            $shipping_types["$vendor_id"]["shipping_type"] = $_SESSION["shipping_type_$vendor_id"];

                            $shipping_types["$vendor_id"]["shipping_cost"] = $_SESSION["shipping_cost_$vendor_id"];

                            $total_shipping_cost += $_SESSION["shipping_cost_$vendor_id"];
                          }
                        }
                      }
                    }

                    $_SESSION["shipping_types"] = $shipping_types;

                    $_SESSION["shipping_cost"] = $total_shipping_cost;

                    $total_cart_price = $total + $total_shipping_cost;

                    ?>

                  </ul>

                </th>

              </tr>

            <?php } ?>

            <tr>

              <th class="text-muted">Tax:</th>

              <th>Ksh 0</th>

            </tr>


            <tr class="total">

              <td>Total:</td>

              <?php if (count($physical_products) > 0) { ?>

                <th class="total-shipping-price">Ksh <?php echo $total_cart_price; ?>.00</th>

              <?php } else { ?>

                <th class="total-shipping-price">Ksh <?php echo $total; ?>.00</th>

              <?php } ?>

            </tr>

            <tr>

              <th colspan="2">



              </th>

            </tr>


            <?php if ($enable_pesapal == "yes") { ?>

              <tr>

                <th colspan="2">

                  <input id="pesapal" type="radio" name="payment_method" value="pesapal">


                  <label for="paypal"> PesaPal </label>

                  <p id="pesapal_desc" class="text-muted">

                    Your order will not be shipped until the funds have cleared in our account.

                  </p>

                </th>

              </tr>

            <?php } ?>

            <?php if ($enable_umspay == "yes") { ?>

              <tr>



                </th>

              </tr>

            <?php } ?>

            <?php if ($enable_stripe == "yes") { ?>

              <tr>

                <th colspan="2">



                </th>

              </tr>

            <?php } ?>




            <?php if ($enable_paypal == "yes") { ?>

              <tr>

                <th colspan="2">

                  <input id="paypal" type="radio" name="payment_method" value="paypal" checked>

                  <label for="paypal">Paypal</label>

                  <p id="paypal_desc" class="text-muted">

                    Pay via PayPal you can pay with your credit card if you don’t have a PayPal account.

                  </p>

                </th>

              </tr>

            <?php } ?>

            <tr>
              <td id="payment-forms-td" colspan="2">
                <form id="offline_form" action="order.php" method="post"><!-- offline Payment form Starts -->
                  <?php if (count($physical_products) > 0) { ?>
                    <input type="hidden" name="amount" value="<?php echo $total_cart_price; ?>">
                  <?php } else { ?>
                    <input type="hidden" name="amount" value="<?php echo $total; ?>">
                  <?php } ?>
                  <input type="submit" id="offline-submit" name="submit" value="Place Order" class="btn btn-success btn-lg" style="border-radius:0px;">
                </form><!-- offline Payment form Starts -->
                <?php
                if ($enable_stripe == "yes") {
                  if (count($physical_products) > 0) {
                    $stripe_total_amount = $total_cart_price * 100;
                  } else {
                    $stripe_total_amount = $total;
                  }
                  include("stripe_config.php");
                ?>

                  <form id="stripe_form" action="stripe_charge.php" method="post">

                    <input type="hidden" name="total_amount" value="<?php echo $total_cart_price; ?>">

                    <input type="hidden" name="stripe_total_amount" value="<?php echo $stripe_total_amount; ?>">

                    <input type="submit" id="stripe-submit" class="btn btn-success btn-lg" value="Procced With Stripe" style="border-radius:0px;" data-name="Computerfever.com" data-description="Pay With Credit Card" data-image="images/stripe-logo.png" data-key="<?php echo $stripe['publishable_key']; ?>" data-amount="<?php echo $stripe_total_amount; ?>" data-currency="<?php echo $stripe['currency_code']; ?>" data-email="<?php echo $customer_email; ?>">

                  </form>


                <?php } ?>





                <?php if ($enable_paypal == "yes") { ?>

                  <form id="paypal_form" action="<?php echo $paypal_url; ?>" method="post"><!-- PayPal form Starts -->

                    <input type="hidden" name="business" value="<?php echo $paypal_email; ?>">

                    <input type="hidden" name="cmd" value="_cart">

                    <input type="hidden" name="upload" value="1">

                    <input type="hidden" name="currency_code" value="<?php echo $paypal_currency_code; ?>">

                    <?php if (count($physical_products) > 0) { ?>

                      <input type="hidden" name="return" value="http://localhost/ecommerce_project/paypal_order.php?c_id=<?php echo $customer_id; ?>&amount=<?php echo $total_cart_price; ?>">

                    <?php } else { ?>

                      <input type="hidden" name="return" value="http://localhost/ecommerce_project/paypal_order.php?c_id=<?php echo $customer_id; ?>&amount=<?php echo $total; ?>">

                    <?php } ?>

                    <input type="hidden" name="cancel_return" value="http://localhost/ecommerce_project/checkout.php">

                    <?php

                    $i = 0;

                    $get_cart = "select * from cart where ip_add='$ip_add'";

                    $run_cart = mysqli_query($con, $get_cart);

                    while ($row_cart = mysqli_fetch_array($run_cart)) {

                      $pro_id = $row_cart['p_id'];

                      $pro_qty = $row_cart['qty'];

                      $pro_price = $row_cart['p_price'];

                      $get_products = "select * from products where product_id='$pro_id'";

                      $run_products = mysqli_query($con, $get_products);

                      $row_products = mysqli_fetch_array($run_products);

                      $product_title = $row_products['product_title'];

                      $i++;

                    ?>

                      <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $product_title; ?>">

                      <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo $i; ?>">

                      <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $pro_price; ?>">

                      <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $pro_qty; ?>">

                    <?php } ?>

                    <input type="hidden" name="shipping_1" value="<?php echo @$_SESSION["shipping_cost"]; ?>">

                    <input type="hidden" name="first_name" value="<?php echo $billing_first_name; ?>">

                    <input type="hidden" name="last_name" value="<?php echo $billing_last_name; ?>">

                    <input type="hidden" name="address1" value="<?php echo $billing_address_1; ?>">

                    <input type="hidden" name="address2" value="<?php echo $billing_address_2; ?>">

                    <input type="hidden" name="city" value="<?php echo $billing_city; ?>">

                    <input type="hidden" name="state" value="<?php echo $billing_state; ?>">

                    <input type="hidden" name="zip" value="<?php echo $billing_postcode; ?>">

                    <input type="hidden" name="night_phone_a" value="<?php echo $customer_contact; ?>">

                    <input type="hidden" name="email" value="<?php echo $customer_email; ?>">

                    <input type="submit" id="paypal-submit" name="submit" value="Proceed With PayPal" class="btn btn-success btn-lg" style="border-radius:0px;">

                  </form><!-- PayPal form Ends -->

                <?php } ?>


                <?php if ($enable_pesapal == "yes") { ?>
                  <form id="pesapal_form" action="pesapal_charge.php" method="post">
                    <input type="text" name="phone_number" placeholder="Enter Phone Number" class="form-control" required>
                    <input type="hidden" name="total_amount" value="<?php echo $total_cart_price; ?>">
                    <input type="submit" id="pesa-submit" class="btn btn-success btn-lg" value="Proceed With PesaPal" style="border-radius:0px;">
                  </form>
                <?php } ?>


                <?php if ($enable_umspay == "yes") { ?>
                  <style>
                    .form-group {
                      margin-bottom: 15px;
                    }

                    .form-control {
                      width: 100%;
                      padding: 10px;
                      border: 1px solid #ccc;
                      border-radius: 4px;
                      box-sizing: border-box;
                      font-size: 16px;
                      margin-bottom: 15px;
                    }

                    .form-control:focus {
                      border-color: #66afe9;
                      outline: none;
                      box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
                    }

                    .btn {
                      width: 100%;
                      padding: 10px;
                      border: none;
                      border-radius: 4px;
                      font-size: 16px;
                      cursor: pointer;
                    }

                    #pesa-submit {
                      border-radius: 0px;
                    }
                  </style>
                  <form id="umspay_form" action="umspay_charge.php" method="post">
                    <input type="text" name="phone_number" placeholder="Enter Phone Number" class="form-control" required>
                    <input type="hidden" name="total_amount" value="<?php echo $total_cart_price; ?>">

                  </form>
                <?php } ?>

              </td>

            </tr>

          </tbody>

        </table>

      </div><!-- box Ends -->

    </div><!-- col-md-4 Ends -->

  <?php } ?>

</div><!--- row Ends -->

<div id="data_response"></div>

<script>
  $(document).ready(function() {

    <?php if (@$_SESSION["is_shipping_address"] == "yes") { ?>

      $('#shipping-details-form-div input,#shipping-details-form-div select').prop("disabled", true);

      $('#shipping-details-form-div').hide();

    <?php } ?>

    $("input[name='is_shipping_address']").click(function() {

      var radio_value = $(this).val();

      if (radio_value == "yes") {

        $('#shipping-details-form-div input,#shipping-details-form-div select').prop("disabled", true);

        $('#shipping-details-form-div').hide();

      } else if (radio_value == "no") {

        $('#shipping-details-form-div input,#shipping-details-form-div select').prop("disabled", false);

        $('#shipping-details-form-div').show();

      }

    });

    $("#shipping-billing-details-form :input").change(function() {

      var form = document.getElementById("shipping-billing-details-form");

      var form_data = new FormData(form);

      var shipping_type = $("input[name='shipping_type']:checked").val();

      var payment_method = $("input[name='payment_method']:checked").val();

      form_data.append("shipping_type", shipping_type);

      form_data.append("payment_method", payment_method);

      $("table").addClass("table-loader");

      $.ajax({

        url: "update_billing_shipping_details.php",

        method: "POST",

        processData: false,

        contentType: false,

        cache: false,

        data: form_data

      }).done(function() {

        $("#checkout-tbody-reload").load("checkout_tbody.php");

        $("table").removeClass("table-loader");

      });

    });

    <?php if (count($physical_products) > 0) { ?>

      $(document).on('change', '.shipping_type', function() {

        var shipping_types = {};

        var total_shipping_cost = Number(0);

        <?php

        foreach ($vendors_ids as $vendor_id) {

          if (isset($physical_products[$vendor_id])) {

        ?>

            shipping_types["<?php echo $vendor_id; ?>"] = {};

            var shipping_type = $("input[name='[<?php echo $vendor_id; ?>][shipping_type]']:checked").val();

            var shipping_cost = Number($("input[name='[<?php echo $vendor_id; ?>][shipping_type]']:checked").data("shipping_cost"));

            shipping_types["<?php echo $vendor_id; ?>"]["shipping_type"] = shipping_type;

            shipping_types["<?php echo $vendor_id; ?>"]["shipping_cost"] = shipping_cost;

            total_shipping_cost += shipping_cost;

        <?php }
        } ?>

        var total = Number(<?php echo $total; ?>);

        var total_cart_price = total + total_shipping_cost;

        var payment_method = $("input[name='payment_method']:checked").val();

        $("table").addClass("table-loader");

        $.ajax({

            url: "change_checkout_shipping.php",

            method: "POST",

            data: {
              total: total,
              shipping_types: shipping_types,
              shipping_cost: total_shipping_cost,
              payment_method: payment_method,
              total_cart_price: total_cart_price
            }

          })
          .done(function(data) {

            $(".total-shipping-price").html("Ksh " + total_cart_price + ".00");

            $("#payment-forms-td").html(data);

            $("table").removeClass("table-loader");

          });

      });

    <?php } ?>

    <?php if ($enable_paypal == "yes" or $enable_stripe == "yes") { ?>

      $('#offline_desc').hide();

      $('#offline_form').hide();

    <?php } ?>

    <?php if ($enable_pesapal == "yes") { ?>

      $('#stripe_desc').hide();

      $('#stripe_form').hide();

      $('#umspay_desc').hide();

      $('#umspay_form').hide();

      $('#pesapal_desc').show();

      $('#pesapal_form').show();

    <?php } ?>


    <?php if ($enable_umspay == "yes") { ?>

      $('#stripe_desc').hide();

      $('#stripe_form').hide();

      $('#pesapal_desc').hide();

      $('#pesapal_form').hide();

      $('#umspay_desc').show();

      $('#umspay_form').show();

    <?php } ?>




    <?php if ($enable_paypal == "yes") { ?>

      $('#stripe_desc').hide();

      $('#stripe_form').hide();

      $('#pesapal_desc').hide();

      $('#pesapal_form').hide();

      $('#umspay_desc').hide();

      $('#umspay_form').hide();

      $('#paypal_desc').show();

      $('#paypal_form').show();





    <?php } ?>

    $('#paypal').click(function() {

      $('#offline_desc').hide();
      $('#offline_form').hide();
      $('#stripe_desc').hide();
      $('#stripe_form').hide();
      $('#pesapal_desc').hide();
      $('#pesapal_form').hide();
      $('#umspay_desc').hide();
      $('#umspay_form').hide();
      $('#paypal_desc').show();
      $('#paypal_form').show();


    });

    $('#stripe').click(function() {

      $('#offline_desc').hide();
      $('#offline_form').hide();
      $('#paypal_desc').hide();
      $('#paypal_form').hide();
      $('#pesapal_desc').hide();
      $('#pesapal_form').hide();
      $('#umspay_desc').hide();
      $('#umspay_form').hide();
      $('#stripe_desc').show();
      $('#stripe_form').show();

    });

    $('#offline').click(function() {

      $('#stripe_desc').hide();
      $('#stripe_form').hide();
      $('#paypal_desc').hide();
      $('#paypal_form').hide();
      $('#pesapal_desc').hide();
      $('#pesapal_form').hide();
      $('#umspay_desc').hide();
      $('#umspay_form').hide();
      $('#offline_desc').show();
      $('#offline_form').show();

    });

    $('#pesapal').click(function() {

      $('#stripe_desc').hide();
      $('#stripe_form').hide();
      $('#paypal_desc').hide();
      $('#paypal_form').hide();
      $('#offline_desc').hide();
      $('#offline_form').hide();
      $('#umspay_desc').hide();
      $('#umspay_form').hide();
      $('#pesapal_desc').show();
      $('#pesapal_form').show();

    });


    $('#umspay').click(function() {

      $('#stripe_desc').hide();
      $('#stripe_form').hide();
      $('#paypal_desc').hide();
      $('#paypal_form').hide();
      $('#offline_desc').hide();
      $('#offline_form').hide();
      $('#pesapal_desc').hide();
      $('#pesapal_form').hide();
      $('#umspay_desc').show();
      $('#umspay_form').show();

    });


    $('#offline-submit').click(function(event) {

      event.preventDefault();

      $('#shipping-billing-details-form').submit(function(event) {

        event.preventDefault();

        $('#offline-submit').click();

      });

      $('#shipping-billing-form-submit').click();

    });


    $('#stripe-submit').click(function(event) {

      event.preventDefault();

      $('#shipping-billing-details-form').submit(function(event) {

        event.preventDefault();

        var $button = $('#stripe-submit'),
          $form = $button.parents('form');
        var opts = $.extend({}, $button.data(), {
          token: function(result) {
            $form.append($('<input>').attr({
              type: 'hidden',
              name: 'stripeToken',
              value: result.id
            })).submit();
          }
        });

        StripeCheckout.open(opts);

      });

      $('#shipping-billing-form-submit').click();

    });


    $('#paypal-submit').click(function(event) {

      event.preventDefault();

      $('#shipping-billing-details-form').submit(function(event) {

        event.preventDefault();

        $('#paypal-submit').click();

      });

      $('#shipping-billing-form-submit').click();

    });


  });
</script>