package com.example.demo.domain.usecase.product.dto;


import com.example.demo.domain.entities.product.Category;

import java.math.BigDecimal;
import java.util.List;

public interface IProductRegistrationData {
    String getDescription();
    String getName();
    BigDecimal getPrice();
    Integer getStockQuantity();
    Category getCategory();
    Boolean getActive();
}
