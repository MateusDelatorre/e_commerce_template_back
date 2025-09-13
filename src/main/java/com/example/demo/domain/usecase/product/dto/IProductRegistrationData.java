package com.example.demo.domain.usecase.product.dto;

import java.util.List;

public interface IProductRegistrationData {
    String getDescription();
    String getName();
    Double getPrice();
    Integer getStockQuantity();
    String getCategory();
    String getImageUrl();
    Boolean getActive();
    List<String> getTags();
    List<String> getOptions();
}
